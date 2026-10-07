# Leave Management System - Design Document

## Overview

The Leave Management System is an integrated feature that extends the existing incident management workflow to track, manage, and automate compliance with HSB 3/2017 leave regulations. The system operates across four main components: leave data capture during HR acknowledgement, a real-time leave management dashboard, automated milestone flagging via scheduled jobs, and a Medical Board review interface. The design emphasizes data integrity, audit traceability, and seamless integration with the existing incident workflow without disrupting current case progression logic.

The system's core mechanism is a calculated field (`days_elapsed`) that automatically determines payment stage eligibility and triggers milestone flags. These flags initiate automatic status transitions and notifications, creating a fully automated escalation path for cases reaching critical leave thresholds. All changes—whether manual (HR/Medical Board decisions) or automatic (scheduler flags)—are tracked in an immutable audit trail for compliance and forensic analysis.

## Architecture

### High-Level Flow

```
Incident Case (HR Acknowledgement Stage 5)
    ↓
HR Captures Leave Information
    ↓
System calculates days_elapsed and payment_stage
    ↓
Leave data persisted + Audit Log created
    ↓
Case appears on Leave Management Dashboard
    ↓
Daily Scheduler checks milestone thresholds
    ↓
├─ At 90 days: Auto-flag + Status→REFERRED_TO_MEDICAL_BOARD + Notify Medical Board
├─ At 180 days: Auto-flag + Status→MEDICAL_DISCHARGE_ELIGIBLE + Notify Medical Board
    ↓
Medical Board Dashboard shows flagged cases
    ↓
Medical Board Member makes decision
    ↓
Decision recorded + Case status updated + Audit Log created
```

### Layering

1. **Database Layer**: New fields on `incidents` table, new `incident_leave_milestones` table for flag history
2. **Model Layer**: `Incident` model enhanced with leave-related methods and relationships
3. **Service Layer**: `LeaveManagementService` handles calculations, flagging, and transitions
4. **Scheduler Layer**: `CheckLeaveMilestonesJob` runs daily to identify and flag cases
5. **Event Layer**: `Case90DayMilestoneReached`, `Case180DayMilestoneReached` events dispatch notifications to appropriate facility-level medical officers
6. **Controller Layer**: `LeaveManagementController`, `MedicalBoardController` handle HTTP requests
7. **View Layer**: Leave form in HR dossier, Leave Management Dashboard, Medical Board Dashboard

## Components and Interfaces

### 1. Database Schema Modifications

#### New Fields on `incidents` Table

```sql
ALTER TABLE incidents ADD COLUMN (
    sick_leave_start_date DATE NULL AFTER routed_to_hr_user_id,
    medical_certificate_provided BOOLEAN DEFAULT FALSE AFTER sick_leave_start_date,
    expected_recovery_date DATE NULL AFTER medical_certificate_provided,
    leave_payment_stage ENUM('FULL_PAY', 'HALF_PAY') NULL AFTER expected_recovery_date,
    leave_status VARCHAR(50) DEFAULT 'ACTIVE' AFTER leave_payment_stage,
    leave_information_saved_at TIMESTAMP NULL AFTER leave_status,
    saved_by_user_id BIGINT UNSIGNED NULL AFTER leave_information_saved_at FOREIGN KEY REFERENCES users(id)
);

-- Indexes for dashboard queries
CREATE INDEX idx_sick_leave_start_date ON incidents(sick_leave_start_date);
CREATE INDEX idx_leave_payment_stage ON incidents(leave_payment_stage);
CREATE INDEX idx_leave_status ON incidents(leave_status);
```

#### New Table: `incident_leave_milestones`

```sql
CREATE TABLE incident_leave_milestones (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    incident_id BIGINT UNSIGNED NOT NULL,
    milestone_type ENUM('90_DAY', '180_DAY') NOT NULL,
    days_elapsed_at_flag INT NOT NULL,
    flag_reason VARCHAR(255) NOT NULL,
    applied_by_system BOOLEAN DEFAULT TRUE,
    applied_by_user_id BIGINT UNSIGNED NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notification_sent_at TIMESTAMP NULL,
    medical_board_decision_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (incident_id) REFERENCES incidents(id),
    FOREIGN KEY (applied_by_user_id) REFERENCES users(id),
    KEY idx_milestone_type (milestone_type),
    KEY idx_applied_at (applied_at)
);
```

#### Audit Log Fields

The existing `audit_logs` table will be used to track all leave management changes with structure:
- `incident_id`: Which incident was modified
- `action`: 'leave_information_saved', 'milestone_flag_applied', 'medical_board_decision'
- `changed_by_user_id`: Who made the change (NULL if system)
- `old_values`: JSON of before state
- `new_values`: JSON of after state
- `metadata`: JSON containing flag_reason, decision_type, etc.

### 2. Incident Model Enhancements

```php
class Incident extends Model {
    // New fillable fields
    protected $fillable = [
        'sick_leave_start_date',
        'medical_certificate_provided',
        'expected_recovery_date',
        'leave_payment_stage',
        'leave_status',
        'leave_information_saved_at',
        'saved_by_user_id',
        // ... existing fields
    ];

    // Relationships
    public function leaveInformationSavedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'saved_by_user_id');
    }

    public function leaveMilestones(): HasMany {
        return $this->hasMany(IncidentLeaveMilestone::class);
    }

    // Calculated Properties
    public function getDaysElapsedAttribute(): ?int {
        if (!$this->sick_leave_start_date) return null;
        return now()->diffInDays($this->sick_leave_start_date);
    }

    public function getRecoveryDaysRemainingAttribute(): ?int {
        if (!$this->expected_recovery_date) return null;
        $remaining = now()->diffInDays($this->expected_recovery_date, false);
        return $remaining;
    }

    public function isFullPayStage(): bool {
        return $this->days_elapsed !== null && $this->days_elapsed <= 90;
    }

    public function isHalfPayStage(): bool {
        return $this->days_elapsed !== null && $this->days_elapsed > 90 && $this->days_elapsed <= 180;
    }

    public function isMedicalDischargeEligible(): bool {
        return $this->days_elapsed !== null && $this->days_elapsed > 180;
    }

    public function hasActiveLeave(): bool {
        return $this->sick_leave_start_date !== null && 
               in_array($this->leave_status, ['ACTIVE', 'SUSPENDED']);
    }

    public function is90DayMilestonePending(): bool {
        return $this->days_elapsed == 90 && 
               !$this->leaveMilestones()->where('milestone_type', '90_DAY')->exists();
    }

    public function is180DayMilestonePending(): bool {
        return $this->days_elapsed >= 180 && 
               !$this->leaveMilestones()->where('milestone_type', '180_DAY')->exists();
    }

    public function getLeavePaymentStage(): string {
        if ($this->days_elapsed === null) return 'UNKNOWN';
        if ($this->days_elapsed <= 90) return 'FULL_PAY';
        if ($this->days_elapsed <= 180) {
            return $this->medical_certificate_provided ? 'HALF_PAY' : 'FULL_PAY';
        }
        return 'MEDICAL_DISCHARGE_ELIGIBLE';
    }
}
```

### 3. LeaveManagementService

```php
class LeaveManagementService {
    
    public function saveLeaveInformation(Incident $incident, array $data, User $user): void {
        // Validate leave data
        $this->validateLeaveData($incident, $data);
        
        // Store old state for audit
        $oldState = [
            'sick_leave_start_date' => $incident->sick_leave_start_date,
            'medical_certificate_provided' => $incident->medical_certificate_provided,
            'expected_recovery_date' => $incident->expected_recovery_date,
            'leave_payment_stage' => $incident->leave_payment_stage,
        ];
        
        // Update incident
        $incident->update([
            'sick_leave_start_date' => $data['sick_leave_start_date'],
            'medical_certificate_provided' => $data['medical_certificate_provided'] ?? false,
            'expected_recovery_date' => $data['expected_recovery_date'] ?? null,
            'leave_payment_stage' => $this->calculatePaymentStage($incident, $data),
            'leave_information_saved_at' => now(),
            'saved_by_user_id' => $user->id,
        ]);
        
        // Create audit log
        AuditLog::create([
            'incident_id' => $incident->id,
            'action' => 'leave_information_saved',
            'changed_by_user_id' => $user->id,
            'old_values' => json_encode($oldState),
            'new_values' => json_encode($incident->only([
                'sick_leave_start_date',
                'medical_certificate_provided',
                'expected_recovery_date',
                'leave_payment_stage',
            ])),
        ]);
    }

    protected function validateLeaveData(Incident $incident, array $data): void {
        $rules = [
            'sick_leave_start_date' => 'required|date|before_or_equal:today|after_or_equal:' . $incident->incident_date->format('Y-m-d'),
            'medical_certificate_provided' => 'boolean',
            'expected_recovery_date' => 'nullable|date|after:sick_leave_start_date|before_or_equal:' . now()->addDays(365)->format('Y-m-d'),
        ];
        
        if ($data['medical_certificate_provided'] ?? false) {
            $rules['expected_recovery_date'] = 'required|' . $rules['expected_recovery_date'];
        }

        Validator::make($data, $rules)->validate();
    }

    protected function calculatePaymentStage(Incident $incident, array $data): string {
        $startDate = Carbon::parse($data['sick_leave_start_date']);
        $daysElapsed = now()->diffInDays($startDate);
        
        if ($daysElapsed <= 90) {
            return 'FULL_PAY';
        }
        
        if ($daysElapsed <= 180 && ($data['medical_certificate_provided'] ?? false)) {
            return 'HALF_PAY';
        }
        
        return 'FULL_PAY';
    }

    public function checkAndApplyMilestones(): array {
        $results = ['flagged_90' => 0, 'flagged_180' => 0, 'errors' => []];
        
        $incidentsWithLeave = Incident::whereNotNull('sick_leave_start_date')
            ->where('leave_status', 'ACTIVE')
            ->get();
        
        foreach ($incidentsWithLeave as $incident) {
            try {
                if ($incident->is90DayMilestonePending()) {
                    $this->apply90DayMilestone($incident);
                    $results['flagged_90']++;
                }
                
                if ($incident->is180DayMilestonePending()) {
                    $this->apply180DayMilestone($incident);
                    $results['flagged_180']++;
                }
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'incident_id' => $incident->id,
                    'error' => $e->getMessage(),
                ];
            }
        }
        
        return $results;
    }

    protected function apply90DayMilestone(Incident $incident): void {
        // Update incident status
        $incident->update([
            'status' => 'REFERRED_TO_MEDICAL_BOARD',
            'leave_payment_stage' => $incident->medical_certificate_provided ? 'HALF_PAY' : 'FULL_PAY',
        ]);
        
        // Record milestone
        IncidentLeaveMilestone::create([
            'incident_id' => $incident->id,
            'milestone_type' => '90_DAY',
            'days_elapsed_at_flag' => $incident->days_elapsed,
            'flag_reason' => '90_DAY_MILESTONE',
            'applied_by_system' => true,
        ]);
        
        // Audit log
        AuditLog::create([
            'incident_id' => $incident->id,
            'action' => 'milestone_flag_applied',
            'changed_by_user_id' => null,
            'old_values' => json_encode(['status' => 'Under_HR_Review']),
            'new_values' => json_encode(['status' => 'REFERRED_TO_MEDICAL_BOARD']),
            'metadata' => json_encode(['milestone_type' => '90_DAY']),
        ]);
        
        // Dispatch event
        event(new Case90DayMilestoneReached($incident));
    }

    protected function apply180DayMilestone(Incident $incident): void {
        // Similar to 90-day but with different status
        $incident->update([
            'status' => 'MEDICAL_DISCHARGE_ELIGIBLE',
        ]);
        
        IncidentLeaveMilestone::create([
            'incident_id' => $incident->id,
            'milestone_type' => '180_DAY',
            'days_elapsed_at_flag' => $incident->days_elapsed,
            'flag_reason' => '180_DAY_MILESTONE',
            'applied_by_system' => true,
        ]);
        
        AuditLog::create([
            'incident_id' => $incident->id,
            'action' => 'milestone_flag_applied',
            'changed_by_user_id' => null,
            'old_values' => json_encode(['status' => 'REFERRED_TO_MEDICAL_BOARD']),
            'new_values' => json_encode(['status' => 'MEDICAL_DISCHARGE_ELIGIBLE']),
            'metadata' => json_encode(['milestone_type' => '180_DAY']),
        ]);
        
        event(new Case180DayMilestoneReached($incident));
    }
}
```

### 4. Scheduler Job: CheckLeaveMilestonesJob

```php
class CheckLeaveMilestonesJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(LeaveManagementService $leaveService): void {
        try {
            $results = $leaveService->checkAndApplyMilestones();
            
            Log::info('Leave milestone check completed', [
                'flagged_90' => $results['flagged_90'],
                'flagged_180' => $results['flagged_180'],
                'errors' => count($results['errors']),
            ]);
            
            // Send summary to admin
            Mail::to(config('app.admin_email'))
                ->send(new LeaveMilestoneSummaryMail($results));
                
        } catch (\Exception $e) {
            Log::error('Leave milestone check failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
```

### 5. Events

```php
class Case90DayMilestoneReached {
    public function __construct(public Incident $incident) {}
}

class Case180DayMilestoneReached {
    public function __construct(public Incident $incident) {}
}
```

### 6. Listeners

```php
class SendMilestoneNotifications {
    public function handle(Case90DayMilestoneReached|Case180DayMilestoneReached $event): void {
        $incident = $event->incident;
        
        // Determine appropriate medical officer for medical board review request
        // All Primary/Secondary/Tertiary facilities → PMD
        // Central Hospital (Quaternary) → CMO
        $medicalOfficer = $this->getAppropriateMedicalOfficer($incident);
        
        if (!$medicalOfficer) {
            Log::warning('No medical officer found for milestone notification', [
                'incident_id' => $incident->id,
                'facility_id' => $incident->facility_id,
            ]);
            return;
        }
        
        // Send email to assigned HR user
        if ($incident->assignedHRUser) {
            Mail::to($incident->assignedHRUser)
                ->send(new LeaveMilestoneNotification($incident, $medicalOfficer, $event::class));
        }
        
        // Send email to appropriate medical officer (PMD or CMO) for medical board review decision
        Mail::to($medicalOfficer)
            ->send(new LeaveMilestoneNotification($incident, $medicalOfficer, $event::class));
        
        // Log notification status
        IncidentLeaveMilestone::where('incident_id', $incident->id)
            ->latest()
            ->first()
            ?->update(['notification_sent_at' => now()]);
        
        // Create audit log
        AuditLog::create([
            'incident_id' => $incident->id,
            'action' => 'milestone_notification_sent',
            'changed_by_user_id' => null,
            'metadata' => json_encode([
                'event_type' => $event::class,
                'notified_medical_officer_id' => $medicalOfficer->id,
                'medical_officer_role' => $medicalOfficer->getRoleNames()->first(),
                'notified_hr_user_id' => $incident->routed_to_hr_user_id,
            ]),
        ]);
    }
    
    protected function getAppropriateMedicalOfficer(Incident $incident): ?User {
        $facility = $incident->facility;
        $facilityType = $facility?->facilityType?->name ?? '';
        
        // Escalation hierarchy for medical board review notification:
        // PRIMARY/SECONDARY/TERTIARY facilities → PMD (Provincial Medical Officer)
        // QUATERNARY (Central Hospital) → CMO (Chief Medical Officer)
        
        return match($facilityType) {
            // PRIMARY LEVEL → PMD
            'Rural Health Centre', 'Health Post', 'Clinic', 'Polyclinic' => 
                $incident->routedToPMD ?? User::role('pmd')->first(),
            
            // SECONDARY LEVEL → PMD
            'District Hospital', 'Rural Hospital', 'Mission Hospital' =>
                $incident->routedToPMD ?? User::role('pmd')->first(),
            
            // TERTIARY LEVEL → PMD
            'Provincial Hospital', 'General Hospital', 'General Hospital / Designated District Referral' =>
                $incident->routedToPMD ?? User::role('pmd')->first(),
            
            // QUATERNARY LEVEL → CMO
            'Central Hospital' =>
                $incident->routedToCMO ?? User::role('cmo')->first(),
            
            default =>
                User::role('pmd')->first(),
        };
    }
}
```

### 7. Controllers

#### LeaveManagementController (Dashboard and Capture)

```php
class LeaveManagementController {
    
    public function dashboard(Request $request) {
        $query = Incident::whereNotNull('sick_leave_start_date');
        
        // Apply filters
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('reference_number', 'like', '%' . $request->search . '%')
                  ->orWhere('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('surname', 'like', '%' . $request->search . '%');
            });
        }
        
        $incidents = $query->paginate(20);
        
        // Calculate statistics
        $stats = [
            'full_pay_count' => Incident::whereIn('leave_payment_stage', ['FULL_PAY'])->count(),
            'half_pay_count' => Incident::where('leave_payment_stage', 'HALF_PAY')->count(),
            'discharge_eligible_count' => Incident::where('status', 'MEDICAL_DISCHARGE_ELIGIBLE')->count(),
        ];
        
        return view('leave.dashboard', compact('incidents', 'stats'));
    }

    public function saveLeaveInformation(Incident $incident, Request $request, LeaveManagementService $service) {
        $this->authorize('update', $incident);
        
        $validated = $request->validate([
            'sick_leave_start_date' => 'required|date',
            'medical_certificate_provided' => 'boolean',
            'expected_recovery_date' => 'nullable|date',
        ]);
        
        $service->saveLeaveInformation($incident, $validated, auth()->user());
        
        return response()->json(['message' => 'Leave information saved']);
    }
}
```

#### MedicalBoardController

```php
class MedicalBoardController {
    
    public function dashboard() {
        $cases = Incident::whereIn('status', ['REFERRED_TO_MEDICAL_BOARD', 'MEDICAL_DISCHARGE_ELIGIBLE'])
            ->with('leaveMilestones')
            ->orderBy('status')
            ->paginate(20);
        
        $stats = [
            'pending_90_day' => $cases->where('status', 'REFERRED_TO_MEDICAL_BOARD')->count(),
            'pending_discharge' => $cases->where('status', 'MEDICAL_DISCHARGE_ELIGIBLE')->count(),
        ];
        
        return view('medical-board.dashboard', compact('cases', 'stats'));
    }

    public function submitDecision(Incident $incident, Request $request) {
        $this->authorize('viewAny', User::class);
        
        $validated = $request->validate([
            'decision' => 'required|in:APPROVE_RETURN_TO_WORK,RECOMMEND_EXTENDED_LEAVE,RECOMMEND_MEDICAL_DISCHARGE',
            'decision_reason' => 'required|string',
        ]);
        
        // Update incident
        $oldStatus = $incident->status;
        $incident->update([
            'status' => match($validated['decision']) {
                'APPROVE_RETURN_TO_WORK' => 'CLEARED_FOR_RETURN',
                'RECOMMEND_EXTENDED_LEAVE' => 'EXTENDED_LEAVE_APPROVED',
                'RECOMMEND_MEDICAL_DISCHARGE' => 'MEDICAL_DISCHARGE_RECOMMENDED',
            },
        ]);
        
        // Audit log
        AuditLog::create([
            'incident_id' => $incident->id,
            'action' => 'medical_board_decision',
            'changed_by_user_id' => auth()->id(),
            'old_values' => json_encode(['status' => $oldStatus]),
            'new_values' => json_encode(['status' => $incident->status]),
            'metadata' => json_encode($validated),
        ]);
        
        return back()->with('success', 'Decision recorded successfully');
    }
}
```

## Data Models

### Leave Information Data Structure

```
Incident Record
├── sick_leave_start_date: DATE
├── medical_certificate_provided: BOOLEAN
├── expected_recovery_date: DATE (nullable)
├── leave_payment_stage: ENUM['FULL_PAY', 'HALF_PAY']
├── leave_status: VARCHAR ['ACTIVE', 'SUSPENDED', 'ENDED']
├── leave_information_saved_at: TIMESTAMP
├── saved_by_user_id: BIGINT
└── Calculated Fields
    ├── days_elapsed: INT (now() - sick_leave_start_date)
    ├── recovery_days_remaining: INT (expected_recovery_date - now())
    ├── is_full_pay_stage: BOOLEAN
    ├── is_half_pay_stage: BOOLEAN
    └── is_discharge_eligible: BOOLEAN

IncidentLeaveMilestone Record
├── incident_id: BIGINT
├── milestone_type: ENUM['90_DAY', '180_DAY']
├── days_elapsed_at_flag: INT
├── flag_reason: VARCHAR
├── applied_by_system: BOOLEAN
├── applied_by_user_id: BIGINT (nullable, NULL if system-applied)
├── applied_at: TIMESTAMP
└── notification_sent_at: TIMESTAMP
```

## Correctness Properties

A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.

### Property 1: Leave Data Persistence Round Trip

**Validates: Requirements 1.7**

*For any* valid leave information provided by an HR user, when saved to the database, retrieving that incident should return identical leave field values (sick_leave_start_date, medical_certificate_provided, expected_recovery_date).

### Property 2: Days Elapsed Calculation Accuracy

**Validates: Requirements 1.3, 10.1**

*For any* sick_leave_start_date, the calculated days_elapsed shall equal DATEDIFF(today, sick_leave_start_date), and shall recalculate correctly when accessed on different calendar dates.

### Property 3: Leave Start Date Must Not Precede Incident Date

**Validates: Requirements 1.2, 7.2**

*For any* incident, attempting to save a sick_leave_start_date that is before the incident_date shall be rejected with a validation error, and no leave record shall be created.

### Property 4: Medical Certificate Required Field Validation

**Validates: Requirements 1.4, 7.3**

*For any* leave submission where medical_certificate_provided is true, the expected_recovery_date must be provided and populated; if not provided, the save operation shall fail with a validation error.

### Property 5: Recovery Date Must Be After Leave Start

**Validates: Requirements 1.5, 7.5**

*For any* expected_recovery_date provided, it must be after the sick_leave_start_date; attempting to save a recovery date before or equal to the start date shall be rejected.

### Property 6: Payment Stage Determination Logic

**Validates: Requirements 1.6, 7.5, 7.6**

*For any* incident with active leave, the leave_payment_stage shall be calculated as: FULL_PAY if days_elapsed ≤ 90, HALF_PAY if 91 ≤ days_elapsed ≤ 180 and medical_certificate_provided is true, otherwise FULL_PAY.

### Property 7: Audit Log Creation on Save

**Validates: Requirements 1.8, 8.1**

*For any* leave information saved, an audit log entry shall be created with old_values containing the previous state, new_values containing the updated state, changed_by_user_id identifying the HR user, and both objects shall be valid JSON.

### Property 8: Dashboard Filters Return Correct Cases

**Validates: Requirements 2.1, 2.9**

*For any* Leave Management Dashboard filtered by facility_id, district_id, or search term, the returned incidents shall only include cases where sick_leave_start_date is not null and match the filter criteria.

### Property 9: Dashboard Groups Cases by Payment Stage

**Validates: Requirements 2.3**

*For any* collection of incidents on the dashboard, cases shall be grouped such that Full_Pay section contains only incidents with days_elapsed ≤ 90, Half_Pay section contains only incidents with 91 ≤ days_elapsed ≤ 180, and Medical_Discharge_Eligible contains only incidents with days_elapsed > 180.

### Property 10: Dashboard Summary Counts Match Actual Cases

**Validates: Requirements 2.4**

*For any* dashboard display, the summary card counts (full_pay_count, half_pay_count, discharge_eligible_count) shall exactly match the number of cases in each corresponding group.

### Property 11: 90-Day Milestone Automatically Flags Case

**Validates: Requirements 3.1**

*For any* incident where sick_leave_start_date is exactly 90 days ago, the next execution of the milestone check shall set case_status to 'REFERRED_TO_MEDICAL_BOARD' and create a corresponding IncidentLeaveMilestone record with milestone_type='90_DAY'.

### Property 12: 90-Day Flag Payment Stage Update

**Validates: Requirements 3.3**

*For any* case flagged at the 90-day milestone where medical_certificate_provided is true, the leave_payment_stage shall be updated to 'HALF_PAY'; if medical_certificate_provided is false, payment stage shall remain 'FULL_PAY'.

### Property 13: 90-Day Flag Creates Audit Log

**Validates: Requirements 3.2**

*For any* case automatically flagged at 90 days, an audit log entry shall be created with action='milestone_flag_applied', changed_by_user_id=null, and metadata containing milestone_type='90_DAY'.

### Property 14: 180-Day Milestone Automatically Flags Case

**Validates: Requirements 4.1**

*For any* incident where sick_leave_start_date is 180 or more days ago and no prior 180-day milestone exists, the next execution of the milestone check shall set case_status to 'MEDICAL_DISCHARGE_ELIGIBLE' and create an IncidentLeaveMilestone record with milestone_type='180_DAY'.

### Property 15: Duplicate 180-Day Flagging Prevented

**Validates: Requirements 4.6**

*For any* case where a 180-day milestone flag already exists, subsequent scheduler runs shall not create duplicate flags; the case shall remain flagged only once.

### Property 16: 180-Day Flag Preserves Leave Data

**Validates: Requirements 4.7**

*For any* case flagged at 180 days, all leave-related fields (sick_leave_start_date, medical_certificate_provided, expected_recovery_date) shall remain unchanged after flagging; only the case status and flag record shall be updated.

### Property 17: Scheduler Processes All Active Leave Cases

**Validates: Requirements 5.2, 5.3**

*For any* incident collection with active leave (leave_status='ACTIVE'), the scheduler job shall process all cases and identify those meeting milestone conditions (days_elapsed=90 or days_elapsed≥180), flagging those without prior flags.

### Property 18: Scheduler Continues on Individual Case Failure

**Validates: Requirements 5.6**

*For any* scheduler job run, if processing one incident raises an exception, the error shall be logged, but the job shall continue processing remaining incidents; all successfully flagged cases shall be recorded.

### Property 19: Medical Board Dashboard Shows Only Flagged Cases

**Validates: Requirements 6.1**

*For any* Medical Board Dashboard display, only incidents with status in ('REFERRED_TO_MEDICAL_BOARD', 'MEDICAL_DISCHARGE_ELIGIBLE') shall be shown; cases with other statuses shall not appear.

### Property 20: Medical Board Decision Recording

**Validates: Requirements 6.7**

*For any* medical board decision submitted, the case status shall be updated to the corresponding status (CLEARED_FOR_RETURN, EXTENDED_LEAVE_APPROVED, or MEDICAL_DISCHARGE_RECOMMENDED), and retrieving the case shall show the updated status.

### Property 21: Medical Board Decision Audit Log

**Validates: Requirements 6.8**

*For any* medical board decision submitted, an audit log entry shall be created with action='medical_board_decision', changed_by_user_id identifying the board member, and metadata containing the full decision_reason.

### Property 22: Leave Information Not Affected by Non-Leave Changes

**Validates: Requirements 9.3**

*For any* incident where leave information has been saved, updating non-leave fields (incident_description, status via other workflows) shall not modify leave-related fields (sick_leave_start_date, medical_certificate_provided, expected_recovery_date).

### Property 23: Expected Recovery Date Calculation

**Validates: Requirements 10.6**

*For any* expected_recovery_date provided, the calculated recovery_days_remaining shall equal DATEDIFF(expected_recovery_date, today); when recovery_days_remaining is negative, it shall display as "Overdue by N days".

### Property 24: Validation Rejects Over-Future Recovery Dates

**Validates: Requirements 1.5**

*For any* expected_recovery_date provided, attempting to save a date more than 365 days in the future shall be rejected with a validation error, and no leave record shall be created.

### Property 25: Validation Rejects Future Leave Start Dates

**Validates: Requirements 7.1**

*For any* sick_leave_start_date provided, attempting to save a date in the future (after today) shall be rejected with a validation error, and no leave record shall be created.

## Error Handling

### Validation Errors

- **Invalid Dates**: Return HTTP 422 with field-specific error messages
- **Missing Required Fields**: Return HTTP 422 with "required field" message
- **Business Rule Violations**: Return HTTP 422 with descriptive error referencing HSB 3/2017 requirement

### System Errors

- **Scheduler Job Failures**: Log to `laravel.log`, send alert email to admin, continue processing other cases
- **Notification Dispatch Failures**: Log error, create audit log entry with failure status, continue case processing
- **Database Constraint Violations**: Log full exception, prevent save, return generic error to user

### Audit Trail

- Every error is logged with incident ID, error type, and stacktrace
- User-facing errors are distinct from internal errors
- All milestone flagging attempts (success/failure) are recorded in audit trail

## Testing Strategy

### Unit Tests

- **Validation Tests**: Test each validation rule with valid and invalid inputs
  - Test date range validation (start date before incident, in future, etc.)
  - Test conditional required fields (recovery date when cert provided)
  - Test payment stage logic at boundaries (90, 91, 180, 181 days)

- **Calculation Tests**: Test calculated fields with various inputs
  - Test days_elapsed calculation with known dates
  - Test recovery_days_remaining with past/future recovery dates
  - Test payment stage determination logic

- **Service Tests**: Test business logic operations
  - Test leave information persistence and retrieval
  - Test audit log creation with old/new values
  - Test milestone detection logic

- **Dashboard Tests**: Test filtering and grouping logic
  - Test filtering by facility/district/search term
  - Test case grouping by payment stage
  - Test summary count accuracy

### Property-Based Tests

- **Property 1: Leave Data Round Trip**
  - Generate random valid leave information, save to database, retrieve and verify all fields match
  - Generate leave information at boundary dates (today, incident date, 365 days future)
  - Run 100+ iterations

- **Property 2: Days Elapsed Calculation**
  - Generate random sick_leave_start_date values, calculate days_elapsed, verify against formula
  - Test with dates from past, present, and near-future
  - Verify calculation changes correctly when accessed on different dates
  - Run 100+ iterations
  - Feature: leave-management-system, Property 2: Days Elapsed Calculation Accuracy

- **Property 6: Payment Stage Determination**
  - Generate incidents at days 0, 45, 90, 91, 135, 180, 181, 365
  - For each, with and without medical certificate
  - Verify payment stage matches expected logic
  - Run 100+ iterations
  - Feature: leave-management-system, Property 6: Payment Stage Determination Logic

- **Property 11: 90-Day Milestone Flagging**
  - Generate incidents with sick_leave_start_date exactly 90 days ago
  - Run scheduler job, verify case flagged with correct status and milestone record created
  - Generate incidents at 89 and 91 days, verify not flagged
  - Run 100+ iterations
  - Feature: leave-management-system, Property 11: 90-Day Milestone Automatically Flags Case

- **Property 14: 180-Day Milestone Flagging**
  - Generate incidents with sick_leave_start_date exactly 180, 181, 365 days ago
  - Run scheduler job, verify cases at 180+ days flagged correctly
  - Verify cases below 180 days not flagged
  - Run 100+ iterations
  - Feature: leave-management-system, Property 14: 180-Day Milestone Automatically Flags Case

- **Property 17: Scheduler Processes All Cases**
  - Generate 50+ incidents with varied leave_start_dates (some at 90, some at 180, some in between)
  - Run scheduler job, count flagged cases, verify equals expected count
  - Run 10+ iterations with different data sets
  - Feature: leave-management-system, Property 17: Scheduler Processes All Active Leave Cases

- **Property 20: Medical Board Decision Recording**
  - Generate flagged cases, submit various decisions, retrieve and verify status updated
  - Verify decision_reason preserved in audit log
  - Test all three decision types
  - Run 100+ iterations
  - Feature: leave-management-system, Property 20: Medical Board Decision Recording

- **Property 24: Recovery Date Validation**
  - Generate dates 1, 364, 365, 366 days in future
  - Verify dates within 365 days accepted, dates beyond rejected
  - Test with and without sick_leave_start_date
  - Run 100+ iterations
  - Feature: leave-management-system, Property 24: Validation Rejects Over-Future Recovery Dates

