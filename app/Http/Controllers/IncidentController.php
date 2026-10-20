<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\District;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\Province;
use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\IncidentAttachment;
use App\Models\IncidentWitnessToken;
use App\Models\FirstAidRecord;
use App\Models\User;
use App\Services\SMSService;
use App\Services\SecureFileUploadService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * IncidentController - Manages incident reporting, verification, and management.
 *
 * SECURITY NOTES (Task 10: Authorization and Security Checks):
 *
 * 1. Role-Based Access Control (RBAC):
 *    - All witness management routes require supervisor role
 *    - CheckWitnessManagementAccess middleware verifies assigned_supervisor_id
 *    - Non-supervisors receive 403 Forbidden
 *    - Cross-supervisor access is blocked
 *
 * 2. Supervisor Responsibility Filtering (Requirement 9.2):
 *    - Witnesses are filtered by incident.assigned_supervisor_id
 *    - Each supervisor can only manage incidents they're assigned to
 *    - Direct SQL queries filter by assigned_supervisor_id
 *
 * 3. SMS Action Audit Logging (Requirement 9.4):
 *    - All SMS sends (initial, reminders) logged to incident_notifications table
 *    - initiated_by_user_id tracks which supervisor sent the SMS
 *    - Timestamp records when action was initiated
 *    - Action type (INITIAL/REMINDER) stored in notification_type
 *
 * 4. SMS Credential Security (Requirement 9.5):
 *    - Raw API keys NEVER logged to application logs
 *    - Only 'api_key_configured' boolean logged, not key itself
 *    - Error messages to UI are generic (no SMS service details exposed)
 *    - Full error details logged only to server logs (not sent to UI)
 *    - Supervisor sees only: status (SENT/FAILED) and timestamp
 *
 * Implementation Details:
 * - sendWitnessReminder(): Requires supervisor role + assigned check
 * - sendWitnessToken(): Same authorization checks, logs all attempts
 * - sendAllWitnessSMS(): Batch send with audit logging for each witness
 * - getWitnessSmsPreview(): Shows only message content, no credentials
 * - incident_notifications table: Complete audit trail of SMS sends
 */
class IncidentController extends Controller
{
    public function create()
    {
        // Optimize: Cache form data with shorter TTL and only load essential fields
        // Use 5-minute cache for frequently accessed data
        $cacheMinutes = 5;
        
        $facilityTypes = cache()->remember('form_facility_types_v2', $cacheMinutes * 60, fn() => 
            FacilityType::select('id', 'name')->get()
        );
        
        $provinces = cache()->remember('form_provinces_v2', $cacheMinutes * 60, fn() => 
            Province::select('id', 'name')->orderBy('name')->get()
        );
        
        $districts = cache()->remember('form_districts_v2', $cacheMinutes * 60, fn() => 
            District::select('id', 'name', 'province_id')->orderBy('name')->get()
        );
        
        // CRITICAL OPTIMIZATION: Only select needed fields from facilities (was loading all 1726 with all fields)
        $facilities = cache()->remember('form_facilities_v2', $cacheMinutes * 60, fn() => 
            Facility::select('id', 'name', 'facility_type_id', 'district_id')
                ->orderBy('name')
                ->get()
        );
        
        $departments = cache()->remember('form_departments_v2', $cacheMinutes * 60, fn() => 
            Department::select('id', 'name')->orderBy('name')->get()
        );
        
        $designations = cache()->remember('form_designations_v2', $cacheMinutes * 60, fn() => 
            Designation::select('id', 'name')->orderBy('name')->get()
        );
        
        $incidentTypes = cache()->remember('form_incident_types_v2', $cacheMinutes * 60, fn() => 
            IncidentType::select('id', 'name')->orderBy('name')->get()
        );

        // Cache supervisor designation IDs
        $supervisorDesignationIds = cache()->remember('supervisor_designation_ids_v2', $cacheMinutes * 60, function() {
            $supervisorDesignations = ['Supervisor', 'Senior Supervisor', 'Department Head', 'Manager', 'Matron', 'Sister-in-Charge', 'Principal Nursing Officer', 'Senior Nursing Officer'];
            return Designation::whereIn('name', $supervisorDesignations)->pluck('id')->toArray();
        });

        // CRITICAL OPTIMIZATION: Simplified supervisor query - include facility_id for filtering
        $supervisors = cache()->remember('form_supervisors_v3', $cacheMinutes * 60, function() use ($supervisorDesignationIds) {
            return User::whereHas('roles', fn($q) => $q->where('name', 'supervisor'))
                ->select('id', 'name', 'designation_id', 'department_id', 'facility_id')
                ->with([
                    'designation:id,name',
                    'department:id,name',
                    'facility:id,name'
                ])
                ->orderBy('name')
                ->get();
        });

        // Build supervisor mappings in memory (fast operation)
        $supervisorsByDesignation = [];
        $supervisorsByDeptIds = [];
        $supervisorsByDeptAndFacility = [];
        
        foreach ($supervisors as $supervisor) {
            // Map by designation
            if ($supervisor->designation_id) {
                if (!isset($supervisorsByDesignation[$supervisor->designation_id])) {
                    $supervisorsByDesignation[$supervisor->designation_id] = [];
                }
                $supervisorsByDesignation[$supervisor->designation_id][] = [
                    'id' => $supervisor->id,
                    'name' => $supervisor->name,
                    'department_id' => $supervisor->department_id,
                    'facility_id' => $supervisor->facility_id,
                ];
            }
            
            // Map by department
            if ($supervisor->department_id && $supervisor->designation_id) {
                if (!isset($supervisorsByDeptIds[$supervisor->department_id])) {
                    $supervisorsByDeptIds[$supervisor->department_id] = [];
                }
                if (!in_array($supervisor->designation_id, $supervisorsByDeptIds[$supervisor->department_id])) {
                    $supervisorsByDeptIds[$supervisor->department_id][] = $supervisor->designation_id;
                }
            }

            // Map by department AND facility
            if ($supervisor->department_id && $supervisor->facility_id && $supervisor->designation_id) {
                $key = $supervisor->department_id . '_' . $supervisor->facility_id;
                if (!isset($supervisorsByDeptAndFacility[$key])) {
                    $supervisorsByDeptAndFacility[$key] = [];
                }
                if (!in_array($supervisor->designation_id, $supervisorsByDeptAndFacility[$key])) {
                    $supervisorsByDeptAndFacility[$key][] = $supervisor->designation_id;
                }
            }
        }

        $supervisorsByDeptJson = json_encode($supervisorsByDeptIds);
        $supervisorsByDeptAndFacilityJson = json_encode($supervisorsByDeptAndFacility);

        return view('incidents.create', compact(
            'facilityTypes',
            'provinces', 
            'districts',
            'facilities',
            'departments',
            'designations',
            'incidentTypes',
            'supervisorsByDeptJson',
            'supervisorsByDesignation',
            'supervisorsByDeptAndFacilityJson'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'designation' => 'required|exists:designations,id',
            'department' => 'required|exists:departments,id',
            'id_number' => 'required|string|max:255',
            'ec_number' => 'required|string|max:255',
            'home_address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'incident_type_id' => 'required|exists:incident_types,id',
            'incident_description' => 'required|string|min:20|max:2000',
            'facility_type' => 'required|exists:facility_types,id',
            'province' => 'required|exists:provinces,id',
            'district' => 'nullable|exists:districts,id',
            'facility' => 'required|exists:facilities,id',
            'supervisor_designation' => 'required|exists:designations,id',
            'supervisor_user_id' => 'required|exists:users,id',
            'nssa_form_wca1' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'initial_medical_records' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'consent_all' => 'required|accepted',
            'witnesses' => 'required|array|min:1',
            'witnesses.*.name' => 'required|string|min:3|max:255',
            'witnesses.*.phone' => ['required', 'string', 'regex:/^(\+263|0)[0-9]{9}$/'],
        ], [
            'consent_all.required' => 'You must consent to submit this report.',
            'consent_all.accepted' => 'You must consent to submit this report.',
            'witnesses.required' => 'At least one witness is required.',
            'witnesses.min' => 'At least one witness is required.',
            'witnesses.*.name.required' => 'Witness name is required.',
            'witnesses.*.name.min' => 'Witness name must be at least 3 characters.',
            'witnesses.*.phone.required' => 'Witness phone number is required.',
            'witnesses.*.phone.regex' => 'Phone number must be in +263 or 07xx format (e.g., +263712345678 or 0712345678).',
        ]);

        // ─── STATUTORY 12-MONTH VALIDATION ENGINE ───
        // Per Department of Social Services requirement: IOD claims must be submitted within 12 months
        $incidentDate = \Carbon\Carbon::parse($validated['incident_date']);
        $daysSinceIncident = $incidentDate->diffInDays(now());
        
        if ($daysSinceIncident > 365) {
            return back()->withInput()->with('error', 
                'In accordance with Health Service Commission circular guidelines, Injury on Duty claims must be submitted within a strict timeframe of 12 months from the date of the incident. This case has expired and cannot be processed electronically.'
            );
        }

        DB::beginTransaction();

        try {
            // Fetch the supervisor's name
            $supervisor = User::find($validated['supervisor_user_id']);
            $supervisorName = $supervisor ? $supervisor->name : 'Unknown';

            // Generate unique reference number
            $referenceNumber = 'HSC-' . date('Y') . '-' . str_pad(
                Incident::whereYear('created_at', date('Y'))->count() + 1,
                6,
                '0',
                STR_PAD_LEFT
            );

            // Create incident record
            $incident = Incident::create([
                'reference_number' => $referenceNumber,
                'first_name' => $validated['first_name'],
                'surname' => $validated['surname'],
                'designation_id' => $validated['designation'],
                'department_id' => $validated['department'],
                'id_number' => $validated['id_number'],
                'ec_number' => $validated['ec_number'],
                'home_address' => $validated['home_address'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
                'incident_date' => $validated['incident_date'],
                'incident_time' => $validated['incident_time'],
                'incident_type_id' => $validated['incident_type_id'],
                'incident_description' => $validated['incident_description'],
                'facility_type_id' => $validated['facility_type'],
                'province_id' => $validated['province'],
                'district_id' => $validated['district'] ?? null,
                'facility_id' => $validated['facility'],
                'supervisor_designation_id' => $validated['supervisor_designation'],
                'assigned_supervisor_id' => $validated['supervisor_user_id'],
                'supervisor_name' => $supervisorName,
                'status' => 'PENDING_SUPERVISOR_VERIFICATION',
                'compliance_status' => 'NONCOMPLIANT',
            ]);

            // Generate first aid token
            $incident->generateFirstAidToken();

            // Handle file uploads
            if ($request->hasFile('nssa_form_wca1')) {
                $this->storeIncidentAttachment(
                    $incident,
                    $request->file('nssa_form_wca1'),
                    'NSSA_FORM_WCA1',
                    auth()->id() ?? 0
                );
            }

            if ($request->hasFile('initial_medical_records')) {
                $this->storeIncidentAttachment(
                    $incident,
                    $request->file('initial_medical_records'),
                    'INITIAL_MEDICAL_TREATMENT',
                    auth()->id() ?? 0
                );
            }

            $incident->updateComplianceStatus();

            // Create witness tokens for each witness
            $witnesses = $validated['witnesses'];
            $witnessTokens = [];
            
            foreach ($witnesses as $witnessData) {
                // Filter out empty witnesses (in case front-end sends empty data)
                if (empty($witnessData['name']) || empty($witnessData['phone'])) {
                    continue;
                }
                
                // Generate secure random token
                $token = \Illuminate\Support\Str::random(32);
                
                // Create witness token record
                $witnessToken = \App\Models\IncidentWitnessToken::create([
                    'incident_id' => $incident->id,
                    'witness_name' => $witnessData['name'],
                    'phone_number' => $witnessData['phone'],
                    'token' => $token,
                    'token_sent_at' => null,
                    'statement_submitted_at' => null,
                    'witness_last_reminder_sent_at' => null,
                ]);
                
                $witnessTokens[] = $witnessToken;
            }

            DB::commit();

            // Dispatch event for witness SMS notifications
            if (count($witnessTokens) > 0) {
                \App\Events\IncidentWithWitnessesSubmitted::dispatch($incident, collect($witnessTokens));
            }

            // Send SMS notifications
            try {
                $smsService = new SMSService();
                $facilityName = $incident->facility?->name ?? 'Your Facility';
                
                // Send confirmation SMS to the incident reporter
                $smsService->sendIncidentReportConfirmation(
                    $validated['phone_number'],
                    $validated['first_name'],
                    $referenceNumber,
                    $facilityName
                );
                Log::info('Incident report confirmation SMS sent', ['incident_id' => $incident->id, 'reference' => $referenceNumber]);
                
                // Send first aid token SMS to the incident reporter
                $smsService->sendFirstAidTokenNotification(
                    $validated['phone_number'],
                    $validated['first_name'],
                    $referenceNumber,
                    $facilityName,
                    $incident->first_aid_token
                );
                Log::info('First aid token SMS sent', ['incident_id' => $incident->id, 'reference' => $referenceNumber, 'token' => $incident->first_aid_token]);
                
                // Send alert SMS to the assigned supervisor
                $smsService->sendIncidentAlertToSupervisor(
                    $supervisor->phone_number,
                    $supervisor->name,
                    $validated['first_name'] . ' ' . $validated['surname'],
                    $referenceNumber,
                    $facilityName
                );
                Log::info('Incident alert SMS sent to supervisor', ['incident_id' => $incident->id, 'supervisor_id' => $supervisor->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send incident SMS notifications', [
                    'incident_id' => $incident->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the incident creation if SMS fails
            }

            // Dispatch event to notify supervisor
            \App\Events\IncidentCreated::dispatch($incident);

            return back()->with('success', "Incident report #{$referenceNumber} submitted successfully!")
                        ->with('referenceNumber', $referenceNumber)
                        ->with('report_id', $incident->id)
                        ->with('first_aid_token', $incident->first_aid_token);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Incident store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return back()->withInput()->with('error', 'An error occurred while submitting the incident report. Please try again. Error: ' . $e->getMessage());
        }
    }

    private function storeIncidentAttachment($incident, $file, $documentType, $userId)
    {
        $secureUploadService = new SecureFileUploadService();
        
        // Generate custom filename
        $customFilename = $incident->reference_number . '_' . $documentType . '_' . time();
        
        // Validate and store file using SecureFileUploadService
        $uploadResult = $secureUploadService->upload(
            $file,
            'incident-attachments/' . $incident->id,
            $customFilename
        );
        
        // Check if upload was successful
        if (!$uploadResult['success']) {
            throw new \Exception($uploadResult['error']);
        }

        return \App\Models\IncidentAttachment::create([
            'incident_id' => $incident->id,
            'document_type' => $documentType,
            'file_name' => $uploadResult['filename'],
            'file_path' => $uploadResult['path'],
            'mime_type' => $uploadResult['mime_type'],
            'file_size' => $uploadResult['size'],
            'upload_status' => 'PENDING',
            'uploaded_by_user_id' => $userId,
        ]);
    }

    /**
     * Show supervisor verification form for an incident (Stage 2).
     */
    /**
     * Display case dossier (READ-ONLY view for verified cases)
     */
    public function caseDossierView(Incident $incident)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to view this case dossier.');
        }

        // Only allow view if case is VERIFIED_SUPERVISOR
        if ($incident->status !== 'VERIFIED_SUPERVISOR') {
            abort(403, 'This case is not in a verified state. Use the regular view instead.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Load related data (without organization)
        $incident->load('facility', 'facilityType', 'department', 'designation', 'witnessTokens');

        return view('incidents.case_dossier_view', compact('incident'));
    }

    public function supervisorView(Incident $incident)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to view this incident.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // If supervisor has already verified the on-duty status (draft saved), redirect to witness step
        if ($incident->supervisor_verified_at !== null) {
            return redirect()->route('incidents.supervisor_witness', $incident->id);
        }

        // Load relationships
        $incident->load([
            'designation',
            'department',
            'facility',
            'facilityType',
            'district',
            'province',
            'supervisorDesignation',
            'incidentType',
            'attachments',
            'firstAidRecords'
        ]);

        return view('incidents.supervisor_verify', compact('incident'));
    }

    /**
     * Track incident by reference number (public route).
     */
    public function track()
    {
        return view('incidents.track');
    }

    /**
     * Search for incident by reference number (public route).
     */
    public function search(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|max:50',
        ]);

        $incident = Incident::with([
            'firstAidRecords',
            'designation',
            'department',
            'facility',
            'district',
            'province',
            'supervisorDesignation'
        ])->where('reference_number', $request->reference_number)->first();

        if (!$incident) {
            return response()->json([
                'success' => false,
                'message' => 'Incident not found. Please check the reference number.'
            ], 404);
        }

        // Format the incident data for display
        $formattedIncident = [
            'id' => $incident->id,
            'reference_number' => $incident->reference_number,
            'first_name' => $incident->first_name,
            'surname' => $incident->surname,
            'designation_name' => $incident->designation?->name ?? 'N/A',
            'department_name' => $incident->department?->name ?? 'N/A',
            'phone_number' => $incident->phone_number,
            'email' => $incident->email,
            'incident_date' => $incident->incident_date,
            'incident_time' => $incident->incident_time,
            'incident_description' => $incident->incident_description,
            'facility_name' => $incident->facility?->name ?? 'N/A',
            'district_name' => $incident->district?->name ?? 'N/A',
            'province_name' => $incident->province?->name ?? 'N/A',
            'supervisor_name' => $incident->supervisor_name,
            'supervisor_designation' => $incident->supervisorDesignation?->name ?? 'N/A',
            'status' => $incident->status ?? 'Pending',
            'created_at' => $incident->created_at,
            'updated_at' => $incident->updated_at,
            'first_aid_records' => $incident->firstAidRecords->map(function($record) {
                return [
                    'id' => $record->id,
                    'nurse_name' => $record->nurse_name,
                    'severity_level' => $record->severity_level
                ];
            })->toArray()
        ];

        return response()->json([
            'success' => true,
            'incident' => $formattedIncident
        ]);
    }
    public function publicUploadDocuments(Request $request)
    {
        $validated = $request->validate([
            'incident_id' => 'required|exists:incidents,id',
            'documents' => 'required|array|min:1|max:13',
            'documents.*.type' => 'required|string|in:doctors_report,injury_report,sick_leave,treatment_records,national_id,banking_details,bank_statement,police_report,death_certificate,heir_certificate,db8_form,termination_letter,marriage_certificate',
            'documents.*.file' => 'required|file|max:10240',
        ], [
            'incident_id.required' => 'Incident ID is required.',
            'incident_id.exists' => 'The specified incident does not exist.',
            'documents.required' => 'Please select at least one file to upload.',
            'documents.max' => 'You can upload a maximum of 13 documents.',
            'documents.*.type.required' => 'Document type is required for all documents.',
            'documents.*.type.in' => 'Invalid document type selected.',
            'documents.*.file.required' => 'File is required for all documents.',
            'documents.*.file.max' => 'Each file must not exceed 10MB.',
        ]);

        try {
            DB::beginTransaction();

            $incident = Incident::findOrFail($validated['incident_id']);
            $uploadedCount = 0;

            // Get the file inputs from request
            $documentFiles = $request->file('documents');

            // Map document types to human-readable names
            $documentTypeLabels = [
                'doctors_report' => 'Doctors_Report',
                'injury_report' => 'Injury_Report_Z1149',
                'sick_leave' => 'Sick_Leave_Forms',
                'treatment_records' => 'Treatment_Records',
                'national_id' => 'National_Registration_Card',
                'banking_details' => 'Banking_Details_Forms',
                'bank_statement' => 'Bank_Statements',
                'police_report' => 'Police_Report',
                'death_certificate' => 'Death_Certificate',
                'heir_certificate' => 'Heir_Certificate',
                'db8_form' => 'DB8_Form',
                'termination_letter' => 'Termination_Letter',
                'marriage_certificate' => 'Marriage_Certificate',
            ];

            // Generate person name from incident data
            $personName = str_replace(' ', '_', trim($incident->first_name . '_' . $incident->surname));
            $personName = preg_replace('/[^a-zA-Z0-9_]/', '', $personName);

            // Track if any witness has submitted (for event dispatch)
            $witnessesWithSubmission = [];
            
            // Initialize SecureFileUploadService
            $secureUploadService = new SecureFileUploadService();

            foreach ($validated['documents'] as $index => $documentData) {
                $file = $documentFiles[$index]['file'];
                $documentType = $documentData['type'];

                // Generate clean filename: PersonName_DocumentType_Timestamp
                $timestamp = now()->format('Ymd_His');
                $documentLabel = $documentTypeLabels[$documentType] ?? 'Document';
                $customFilename = "{$personName}_{$documentLabel}_{$timestamp}";

                // Validate and store file using SecureFileUploadService
                $uploadResult = $secureUploadService->upload(
                    $file,
                    'incident-attachments/' . $incident->id,
                    $customFilename
                );

                // Check if upload was successful
                if (!$uploadResult['success']) {
                    DB::rollback();
                    
                    Log::warning('File upload rejected by security validation', [
                        'incident_id' => $incident->id,
                        'document_type' => $documentType,
                        'error' => $uploadResult['error'],
                        'ip_address' => $request->ip(),
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'File upload failed: ' . $uploadResult['error'],
                    ], 400);
                }

                $attachment = IncidentAttachment::create([
                    'incident_id' => $incident->id,
                    'document_type' => $documentType,
                    'file_name' => $uploadResult['filename'],
                    'file_path' => $uploadResult['path'],
                    'mime_type' => $uploadResult['mime_type'],
                    'file_size' => $uploadResult['size'],
                    'upload_status' => 'PENDING',
                    'upload_notes' => 'Document uploaded from public track page',
                    'uploaded_by_user_id' => null, // No user since it's public
                ]);

                // Dispatch event to notify assigned HR
                event(new \App\Events\IncidentDocumentUploaded($incident, $attachment, $documentType, $uploadedCount + 1));

                $uploadedCount++;
            }

            // Update witness submission timestamp for any pending witnesses
            // Find witnesses that have not yet submitted (statement_submitted_at is null)
            $pendingWitnesses = $incident->witnessTokens()
                ->whereNull('statement_submitted_at')
                ->get();

            // Update each pending witness only once (on first upload)
            foreach ($pendingWitnesses as $witnessToken) {
                $witnessToken->update([
                    'statement_submitted_at' => now(),
                ]);
                $witnessesWithSubmission[] = $witnessToken;
            }

            DB::commit();

            // Dispatch WitnessStatementSubmitted event for each witness that just submitted
            foreach ($witnessesWithSubmission as $witnessToken) {
                event(new \App\Events\WitnessStatementSubmitted($incident, $witnessToken));
            }

            Log::info('Public incident documents uploaded', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'document_count' => $uploadedCount,
                'witnesses_submitted' => count($witnessesWithSubmission),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $uploadedCount . ' document(s) uploaded successfully. They will be reviewed and linked to your case.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Public document upload failed', [
                'incident_id' => $validated['incident_id'] ?? null,
                'error' => $e->getMessage(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading documents. Please try again.',
            ], 500);
        }
    }


    /**
     * Download incident report as PDF.
     */
    public function downloadPdf($referenceNumber)
    {
        $incident = Incident::where('reference_number', $referenceNumber)->firstOrFail();

        // Generate PDF using the existing PDF view
        $pdf = Pdf::loadView('incidents.pdf', ['incident' => $incident])
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        return $pdf->download('incident_' . $referenceNumber . '.pdf');
    }

    /**
     * Handle supervisor verification submission (Stage 2).
     */
    public function supervisorVerify(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'You are not the assigned supervisor for this incident.');
        }

        // Validate on-duty confirmation only
        $validated = $request->validate([
            'on_duty_confirmation' => 'required|in:0,1',
        ], [
            'on_duty_confirmation.required' => 'You must confirm whether the employee was on duty.',
        ]);

        try {
            DB::beginTransaction();

            // If rejected, update status and redirect to dashboard
            if ($validated['on_duty_confirmation'] == 0) {
                $incident->update([
                    'supervisor_verified_at' => now(),
                    'is_work_related' => false,
                    'status' => 'REJECTED_SUPERVISOR',
                ]);

                DB::commit();

                return redirect()->route('dashboards.supervisor')
                    ->with('success', "Incident {$incident->reference_number} has been rejected.")
                    ->with('verification_status', 'rejected');
            }

            // If confirmed on-duty, store verification timestamp and mark as work-related
            $incident->update([
                'supervisor_verified_at' => now(),
                'is_work_related' => true,
            ]);

            DB::commit();

            return redirect()->route('incidents.supervisor_witness', $incident->id)
                ->with('success', 'Please now provide witness information for this incident.');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Supervisor verification failed', [
                'incident_id' => $incident->id,
                'supervisor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while submitting your verification. Please try again.');
        }
    }

    public function supervisorWitness(Incident $incident)
        {
            $user = auth()->user();

            // Verify supervisor role
            if (!$user->hasRole('supervisor')) {
                abort(403, 'Access denied. You must have supervisor role to access witness management.');
            }

            // Verify that the logged-in user is the assigned supervisor
            if ($incident->assigned_supervisor_id !== $user->id) {
                abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
            }

            // Only allow access if incident has been verified by supervisor
            if (!$incident->supervisor_verified_at) {
                abort(403, 'This incident must be verified before proceeding to witness step.');
            }

            // Check if all witnesses have submitted their statements
            if ($incident->allWitnessStatementsReceived()) {
                return redirect()
                    ->route('incidents.hod_report', ['incident' => $incident])
                    ->with('info', 'All witness statements have been received. Proceeding to Head of Department report.');
            }

            // Load witness tokens for this incident
            $witnessTokens = $incident->witnessTokens;

            return view('incidents.supervisor_witness', compact('incident', 'witnessTokens'));
        }

    /**
     * Display supervisor witness management dashboard (Task 5.1, 5.2, 5.3, 5.4)
     * Shows witness submission status and allows sending reminders
     */
    public function supervisorWitnessDashboard(Incident $incident)
    {
        $user = auth()->user();

        // Task 5.3: Verify supervisor role on route access
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to access witness management.');
        }

        // Task 5.3: Filter witnesses to only those for incidents supervisor manages
        // Block access to other supervisors' incidents
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Load witness tokens with related attachments for download functionality
        $witnessTokens = $incident->witnessTokens()
            ->with('attachments')
            ->get();

        // Task 7.2: Load SMS history from incident_notifications table
        // Task 7.3: Eager load initiatedBy user for audit display
        // Group SMS history by witness_token_id for easy access in view
        $smsHistory = \App\Models\IncidentNotification::where('incident_id', $incident->id)
            ->whereIn('notification_type', ['WITNESS_INITIAL_SMS', 'WITNESS_REMINDER_SMS'])
            ->with('initiatedBy')
            ->orderBy('sent_at', 'desc')
            ->get()
            ->groupBy('witness_token_id');

        // Task 5.1: Calculate witness count statistics
        $submittedCount = $witnessTokens->where('statement_submitted_at', '!=', null)->count();
        $totalCount = $witnessTokens->count();

        // Load incident details
        $incident->load(['facility', 'department', 'designation']);

        return view('incidents.supervisor_witness_dashboard', compact(
            'incident',
            'witnessTokens',
            'submittedCount',
            'totalCount',
            'smsHistory'
        ));
    }

    /**
     * Send reminder SMS to a pending witness (Task 6.1, 6.2, 6.3, 6.4)
     */
    public function sendWitnessReminder(Request $request, Incident $incident, $tokenId)
    {
        $user = auth()->user();

        // Task 6.1: Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. You must have supervisor role to send reminders.',
            ], 403);
        }

        // Task 6.1: Verify supervisor authorization - only assigned supervisor can send reminders
        if ($incident->assigned_supervisor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. You are not the assigned supervisor for this incident.',
            ], 403);
        }

        try {
            $witnessToken = $incident->witnessTokens()->findOrFail($tokenId);

            // Task 6.1: Check witness has not submitted (isPending())
            if (!$witnessToken->isPending()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot send reminder to a witness who has already submitted their statement.',
                ], 422);
            }

            // Task 6.2: Dispatch reminder SMS via SMS Service
            $smsService = new SMSService();
            $facilityName = $incident->facility?->name ?? 'Your Facility';
            
            // Format reminder message
            $message = "HSC-OHS REMINDER: Please submit your witness statement for incident {$incident->reference_number}. " .
                       "Visit: " . url("/incidents/witness/{$witnessToken->token}");

            try {
                $smsService->send($witnessToken->phone_number, $message);
                
                // Task 6.3: Update witness_last_reminder_sent_at timestamp
                $witnessToken->update([
                    'witness_last_reminder_sent_at' => now(),
                ]);

                // Task 6.2: Log SMS send in incident_notifications table
                // Task 7.3: Include initiated_by_user_id for audit logging
                \App\Models\IncidentNotification::create([
                    'incident_id' => $incident->id,
                    'witness_token_id' => $witnessToken->id,
                    'notification_type' => 'WITNESS_REMINDER_SMS',
                    'notification_channel' => 'sms',
                    'recipient_phone' => $witnessToken->phone_number,
                    'status' => 'SENT',
                    'message_content' => $message,
                    'initiated_by_user_id' => $user->id,
                    'sent_at' => now(),
                ]);

                Log::info('Witness reminder SMS sent', [
                    'incident_id' => $incident->id,
                    'witness_token_id' => $witnessToken->id,
                    'supervisor_id' => $user->id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Reminder SMS sent successfully to ' . $witnessToken->witness_name,
                    'last_reminder_sent' => $witnessToken->witness_last_reminder_sent_at->format('d M Y, H:i'),
                ]);

            } catch (\Exception $smsError) {
                // Task 6.4: Handle SMS failure
                Log::error('Witness reminder SMS failed', [
                    'incident_id' => $incident->id,
                    'witness_token_id' => $witnessToken->id,
                    'error' => $smsError->getMessage(),
                ]);

                // Log failed attempt
                // Task 7.3: Include initiated_by_user_id for audit logging of failed attempts
                \App\Models\IncidentNotification::create([
                    'incident_id' => $incident->id,
                    'witness_token_id' => $witnessToken->id,
                    'notification_type' => 'WITNESS_REMINDER_SMS',
                    'notification_channel' => 'sms',
                    'recipient_phone' => $witnessToken->phone_number,
                    'status' => 'FAILED',
                    'message_content' => $message,
                    'error_message' => $smsError->getMessage(),
                    'initiated_by_user_id' => $user->id,
                    'sent_at' => now(),
                ]);

                // Task 10.4: Don't expose SMS service details to UI - return generic error
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send SMS reminder. Please try again or contact support.',
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Witness reminder operation failed', [
                'incident_id' => $incident->id,
                'token_id' => $tokenId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the reminder.',
            ], 500);
        }
    }


    public function generateWitnessToken(Incident $incident)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to generate witness tokens.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Only allow access if incident has been verified by supervisor
        if (!$incident->supervisor_verified_at) {
            abort(403, 'This incident must be verified before proceeding to witness step.');
        }

        try {
            // Check maximum witness limit (2 only)
            $witnessCount = $incident->witnessTokens()->count();
            if ($witnessCount >= 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum 2 witnesses allowed. You already have ' . $witnessCount . ' witness(es).',
                ], 422);
            }

            // Generate a unique token
            $token = \App\Models\IncidentWitnessToken::generateToken();

            // Create witness token record
            $witnessToken = $incident->witnessTokens()->create([
                'token' => $token,
                'witness_name' => null,
                'phone_number' => null,
            ]);

            return response()->json([
                'success' => true,
                'token_id' => $witnessToken->id,
                'token' => $token,
                'message' => 'Witness token generated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Witness token generation failed', [
                'incident_id' => $incident->id,
                'supervisor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate witness token.',
            ], 500);
        }
    }

    public function updateWitnessToken(Request $request, Incident $incident, $tokenId)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to update witness tokens.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Only allow access if incident has been verified by supervisor
        if (!$incident->supervisor_verified_at) {
            abort(403, 'This incident must be verified before proceeding to witness step.');
        }

        $validated = $request->validate([
            'witness_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        try {
            $witnessToken = $incident->witnessTokens()->findOrFail($tokenId);

            // Update witness details
            $witnessToken->update([
                'witness_name' => $validated['witness_name'],
                'phone_number' => $validated['phone_number'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Witness details updated successfully.',
                'token' => $witnessToken->token,
                'witness_name' => $witnessToken->witness_name,
                'phone_number' => $witnessToken->phone_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Witness token update failed', [
                'incident_id' => $incident->id,
                'token_id' => $tokenId,
                'supervisor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update witness details.',
            ], 500);
        }
    }

    /**
     * Real-time endpoint: Check if all witnesses have submitted statements.
     * Used by supervisor witness view to poll for witness submission status.
     */


    public function sendWitnessToken(Request $request, Incident $incident, $tokenId)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to send witness tokens.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Only allow access if incident has been verified by supervisor
        if (!$incident->supervisor_verified_at) {
            abort(403, 'This incident must be verified before proceeding to witness step.');
        }

        try {
            $witnessToken = $incident->witnessTokens()->findOrFail($tokenId);

            // Validate witness has name and phone
            if (!$witnessToken->witness_name || !$witnessToken->phone_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'Witness name and phone number are required before sending.',
                ], 422);
            }

            // Format phone number
            $smsService = new SMSService();
            $formattedPhone = $smsService->formatPhoneNumber($witnessToken->phone_number);

            // Build witness statement link
            $witnessLink = route('incidents.witness_statement', ['token' => $witnessToken->token]);

            // Format message for logging
            $message = "HSC-OHS: Please submit your witness statement for incident {$incident->reference_number}. " .
                      "Visit: {$witnessLink}";

            try {
                $smsService->sendWitnessTokenNotification(
                    $formattedPhone,
                    $witnessToken->witness_name,
                    $incident->reference_number,
                    $incident->first_name . ' ' . $incident->surname,
                    $incident->incident_date->format('d M Y'),
                    $witnessLink,
                    $incident->facility->name ?? 'the facility'
                );

                // Mark token as sent
                $witnessToken->update([
                    'token_sent_at' => now(),
                ]);

                // Task 10.3: Audit log SMS send with supervisor info
                \App\Models\IncidentNotification::create([
                    'incident_id' => $incident->id,
                    'witness_token_id' => $witnessToken->id,
                    'notification_type' => 'WITNESS_INITIAL_SMS',
                    'notification_channel' => 'sms',
                    'recipient_phone' => $formattedPhone,
                    'status' => 'SENT',
                    'message_content' => $message,
                    'initiated_by_user_id' => $user->id,
                    'sent_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'SMS with witness link sent successfully to ' . $witnessToken->phone_number,
                ]);
            } catch (\Exception $smsError) {
                // Log failure
                Log::error('Witness token SMS send failed', [
                    'incident_id' => $incident->id,
                    'token_id' => $tokenId,
                    'supervisor_id' => $user->id,
                    'error' => $smsError->getMessage(),
                ]);

                // Task 10.3: Audit log failed SMS attempt
                \App\Models\IncidentNotification::create([
                    'incident_id' => $incident->id,
                    'witness_token_id' => $witnessToken->id,
                    'notification_type' => 'WITNESS_INITIAL_SMS',
                    'notification_channel' => 'sms',
                    'recipient_phone' => $formattedPhone,
                    'status' => 'FAILED',
                    'message_content' => $message,
                    'error_message' => $smsError->getMessage(),
                    'initiated_by_user_id' => $user->id,
                    'sent_at' => now(),
                ]);

                // Task 10.4: Don't expose SMS service details to UI - return generic error
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send SMS. Please try again or contact support.',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Witness token SMS send failed', [
                'incident_id' => $incident->id,
                'token_id' => $tokenId,
                'supervisor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send witness SMS. Please try again or contact support.',
            ], 500);
        }
    }

    /**
     * Delete a witness token
     */
    public function deleteWitnessToken(Request $request, Incident $incident, $tokenId)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to delete witness tokens.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Only allow access if incident has been verified by supervisor
        if (!$incident->supervisor_verified_at) {
            abort(403, 'This incident must be verified before proceeding to witness step.');
        }

        try {
            $witnessToken = $incident->witnessTokens()->findOrFail($tokenId);

            // Don't allow deletion if SMS has already been sent
            if ($witnessToken->token_sent_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove a witness after SMS has been sent.',
                ], 422);
            }

            $witnessToken->delete();

            return response()->json([
                'success' => true,
                'message' => 'Witness removed successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Witness token deletion failed', [
                'incident_id' => $incident->id,
                'token_id' => $tokenId,
                'supervisor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove witness: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send SMS to all witnesses at once
     */
    /**
     * Generate SMS preview for all witnesses
     */
    public function getWitnessSmsPreview(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to preview witness SMS.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        try {
            // Get witness tokens that haven't been sent yet
            $witnessTokens = $incident->witnessTokens()
                ->whereNull('token_sent_at')
                ->whereNotNull('witness_name')
                ->whereNotNull('phone_number')
                ->first();

            if (!$witnessTokens) {
                return response()->json([
                    'success' => false,
                    'message' => 'No witnesses with saved details found.',
                ], 422);
            }

            // Generate time-based greeting
            $hour = now()->hour;
            if ($hour >= 5 && $hour < 12) {
                $greeting = "Good Morning";
            } elseif ($hour >= 12 && $hour < 17) {
                $greeting = "Good Afternoon";
            } else {
                $greeting = "Good Evening";
            }

            // Generate sample SMS text
            $smsService = new SMSService();
            $witnessLink = route('incidents.witness_statement', ['token' => $witnessTokens->token]);
            
            $injuredPerson = $incident->first_name . ' ' . $incident->surname;
            $facilityName = $incident->facility->name ?? 'the facility';
            
            $smsText = $greeting . " {$witnessTokens->witness_name},\n\n"
                . "You have been identified as a witness to an incident at the facility.\n\n"
                . "Injured Person: " . $injuredPerson . "\n"
                . "Date of Incident: {$incident->incident_date->format('d M Y')}\n"
                . "Location: " . $facilityName . "\n\n"
                . "Please provide your statement using this link:\n"
                . $witnessLink . "\n\n"
                . "Thank you,\nHealth Service Commission";

            return response()->json([
                'success' => true,
                'sms_text' => $smsText,
                'witness_count' => $incident->witnessTokens()
                    ->whereNull('token_sent_at')
                    ->whereNotNull('witness_name')
                    ->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('SMS preview generation failed', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate SMS preview.',
            ], 500);
        }
    }

    public function sendAllWitnessSMS(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to send witness SMS.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Only allow access if incident has been verified by supervisor
        if (!$incident->supervisor_verified_at) {
            abort(403, 'This incident must be verified before proceeding to witness step.');
        }

        try {
            // Get all witness tokens that haven't been sent yet and have details
            $witnessTokens = $incident->witnessTokens()
                ->whereNull('token_sent_at')
                ->whereNotNull('witness_name')
                ->whereNotNull('phone_number')
                ->get();

            if ($witnessTokens->count() < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'At least 1 witness with saved details is required.',
                ], 422);
            }

            $smsService = new SMSService();
            $sentCount = 0;
            $failedCount = 0;

            foreach ($witnessTokens as $witnessToken) {
                try {
                    // Format phone number
                    $formattedPhone = $smsService->formatPhoneNumber($witnessToken->phone_number);

                    // Build witness statement link
                    $witnessLink = route('incidents.witness_statement', ['token' => $witnessToken->token]);

                    // Format message for logging
                    $message = "HSC-OHS: Please submit your witness statement for incident {$incident->reference_number}. " .
                              "Visit: {$witnessLink}";

                    // Send SMS with witness link
                    $result = $smsService->sendWitnessTokenNotification(
                        $formattedPhone,
                        $witnessToken->witness_name,
                        $incident->reference_number,
                        $incident->first_name . ' ' . $incident->surname,
                        $incident->incident_date->format('d M Y'),
                        $witnessLink,
                        $incident->facility->name ?? 'the facility'
                    );

                    if ($result) {
                        // Mark token as sent
                        $witnessToken->update([
                            'token_sent_at' => now(),
                        ]);

                        // Task 10.3: Audit log SMS send with supervisor info
                        \App\Models\IncidentNotification::create([
                            'incident_id' => $incident->id,
                            'witness_token_id' => $witnessToken->id,
                            'notification_type' => 'WITNESS_INITIAL_SMS',
                            'notification_channel' => 'sms',
                            'recipient_phone' => $formattedPhone,
                            'status' => 'SENT',
                            'message_content' => $message,
                            'initiated_by_user_id' => $user->id,
                            'sent_at' => now(),
                        ]);

                        $sentCount++;
                    } else {
                        // Task 10.3: Audit log failed SMS attempt
                        \App\Models\IncidentNotification::create([
                            'incident_id' => $incident->id,
                            'witness_token_id' => $witnessToken->id,
                            'notification_type' => 'WITNESS_INITIAL_SMS',
                            'notification_channel' => 'sms',
                            'recipient_phone' => $formattedPhone,
                            'status' => 'FAILED',
                            'message_content' => $message,
                            'initiated_by_user_id' => $user->id,
                            'sent_at' => now(),
                        ]);

                        $failedCount++;
                    }
                } catch (\Exception $e) {
                    $witnessLink = route('incidents.witness_statement', ['token' => $witnessToken->token]);
                    $formattedPhone = $smsService->formatPhoneNumber($witnessToken->phone_number);
                    $message = "HSC-OHS: Please submit your witness statement for incident {$incident->reference_number}. " .
                              "Visit: {$witnessLink}";

                    Log::error('Individual witness SMS failed', [
                        'witness_token_id' => $witnessToken->id,
                        'error' => $e->getMessage(),
                    ]);

                    // Task 10.3: Audit log exception with error details
                    \App\Models\IncidentNotification::create([
                        'incident_id' => $incident->id,
                        'witness_token_id' => $witnessToken->id,
                        'notification_type' => 'WITNESS_INITIAL_SMS',
                        'notification_channel' => 'sms',
                        'recipient_phone' => $formattedPhone,
                        'status' => 'FAILED',
                        'message_content' => $message,
                        'error_message' => $e->getMessage(),
                        'initiated_by_user_id' => $user->id,
                        'sent_at' => now(),
                    ]);

                    $failedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
                'message' => "SMS sent to {$sentCount} witness(es) successfully." . ($failedCount > 0 ? " {$failedCount} failed." : ''),
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk witness SMS send failed', [
                'incident_id' => $incident->id,
                'supervisor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            // Task 10.4: Don't expose SMS service details to UI - return generic error
            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS messages. Please try again or contact support.',
            ], 500);
        }
    }

    /**
     * Real-time endpoint: Check if all witnesses have submitted statements.
     * Used by supervisor witness view to poll for witness submission status.
     */
    public function getWitnessStatus(Incident $incident)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            return response()->json(['error' => 'Access denied. You must have supervisor role.'], 403);
        }

        // Verify assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            return response()->json(['error' => 'Access denied. You are not the assigned supervisor.'], 403);
        }

        $allReceived = $incident->allWitnessStatementsReceived();
        $pendingCount = $incident->getPendingWitnessCount();

        return response()->json([
            'all_received' => $allReceived,
            'pending_count' => $pendingCount,
            'status' => $incident->status,
        ]);
    }


    public function submitWitnessVerification(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to submit witness verification.');
        }

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Only allow access if incident has been verified by supervisor
        if (!$incident->supervisor_verified_at) {
            abort(403, 'This incident must be verified before proceeding to witness step.');
        }

        try {
            DB::beginTransaction();

            // Determine review pool using smart routing
            $reviewPool = $incident->determineReviewPool();

            // Update incident status and assign to review pool
            $incident->update([
                'status' => 'VERIFIED_SUPERVISOR',
                'review_pool' => $reviewPool,
            ]);

            // Get the appropriate HR role based on review pool
            $hrRoleMap = [
                'DISTRICT_HR_REVIEW' => 'district_hr',
                'HOSPITAL_HR_REVIEW' => 'hospital_hr',
                'CENTRAL_HR_REVIEW' => 'central_hr',
            ];

            $hrRole = $hrRoleMap[$reviewPool] ?? 'district_hr';

            // Get users in the review pool role
            $reviewers = User::whereHas('roles', function ($query) use ($hrRole) {
                $query->where('name', $hrRole);
            })->get();

            // Send notifications to all reviewers in the pool
            foreach ($reviewers as $reviewer) {
                $reviewer->notify(new \App\Notifications\IncidentHRReviewNotification($incident, $reviewPool));
            }

            DB::commit();

            return redirect()->route('dashboards.supervisor')
                ->with('success', "Incident {$incident->reference_number} has been verified and assigned to {$reviewPool}.")
                ->with('verification_status', 'verified');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Witness verification submission failed', [
                'incident_id' => $incident->id,
                'supervisor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while submitting witness verification. Please try again.');
        }
    }

    /**
     * Display witness statement form (public route, accessed via token).
     */
    public function witnessStatement($token)
    {
        // Find the witness token
        $witnessToken = \App\Models\IncidentWitnessToken::where('token', $token)->firstOrFail();

        // Check if token is valid (not expired and statement not yet submitted)
        if (!$witnessToken->isValid()) {
            return view('incidents.witness_access_denied');
        }

        // Get the incident
        $incident = $witnessToken->incident;

        return view('incidents.witness_statement', compact('incident', 'witnessToken'));
    }
    /**
     * Submit a witness statement via token.
     */
    public function witnessSubmit(Request $request, $token)
    {
        // Find the witness token
        $witnessToken = \App\Models\IncidentWitnessToken::where('token', $token)->firstOrFail();

        // Check if token is valid (not expired and statement not yet submitted)
        if (!$witnessToken->isValid()) {
            return view('incidents.witness_access_denied');
        }

        // Validate statement
        $validated = $request->validate([
            'statement' => 'required|string|min:50|max:5000',
        ], [
            'statement.required' => 'Please provide a witness statement.',
            'statement.min' => 'Statement must be at least 50 characters.',
            'statement.max' => 'Statement cannot exceed 5000 characters.',
        ]);

        try {
            // Submit the statement
            $witnessToken->submitStatement($validated['statement']);

            // Get the incident for event dispatch
            $incident = $witnessToken->incident;

            // Dispatch event when witness statement is submitted
            event(new \App\Events\WitnessStatementSubmitted($incident, $witnessToken));

            // Log the submission
            \Illuminate\Support\Facades\Log::info('Witness statement submitted', [
                'token_id' => $witnessToken->id,
                'incident_id' => $witnessToken->incident_id,
                'submission_timestamp' => $witnessToken->statement_submitted_at,
            ]);

            return view('incidents.witness_statement_submitted', compact('witnessToken'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Witness statement submission failed', [
                'token_id' => $witnessToken->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while submitting your statement. Please try again.');
        }
    }


    /**
     * Display Head of Department report form.
     */
    public function headOfDepartmentReport(Incident $incident)
    {
        $user = auth()->user();

        // Verify supervisor access
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'You are not the assigned supervisor for this incident.');
        }

        // Check if incident has been verified by supervisor
        if (!$incident->supervisor_verified_at) {
            return view('incidents.hod_report_access_denied', [
                'incident' => $incident,
                'reason' => 'not_verified',
                'message' => 'This incident must be verified before proceeding to the Head of Department report stage.',
                'action' => 'Please complete the supervisor verification step first.'
            ]);
        }

        // Check if witness stage has been completed
        $witnessTokens = $incident->witnessTokens;
        if ($witnessTokens->isEmpty()) {
            return view('incidents.hod_report_access_denied', [
                'incident' => $incident,
                'reason' => 'no_witnesses',
                'message' => 'No witness tokens have been generated yet.',
                'action' => 'Please add and send witness notifications before proceeding to the Head of Department report.'
            ]);
        }

        // CRITICAL FIX: Stage 4 Unlock Gate - Block access until ALL witnesses have submitted
        if (!$incident->allWitnessStatementsReceived()) {
            $pendingCount = $incident->getPendingWitnessCount();
            $totalWitnesses = $witnessTokens->count();
            return view('incidents.hod_report_access_denied', [
                'incident' => $incident,
                'reason' => 'pending_witnesses',
                'message' => "Waiting for witness statements (" . ($totalWitnesses - $pendingCount) . " of {$totalWitnesses} received).",
                'pendingCount' => $pendingCount,
                'totalWitnesses' => $totalWitnesses,
                'action' => 'All witnesses must submit their statements before you can proceed to the Head of Department report.'
            ]);
        }

        // Get all witness tokens with their statements
        $witnessStatements = $incident->getSubmittedWitnessStatements();

        return view('incidents.head_of_department_report', compact('incident', 'witnessStatements'));
    }

    /**
     * Store Head of Department report.
     */
    public function storeHeadOfDepartmentReport(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify the user is Head of Department or Supervisor for this incident
        $isHeadOfDept = $user->hasAnyRole(['head_of_department', 'department_head']) || 
                       $incident->assigned_supervisor_id === $user->id;

        if (!$isHeadOfDept) {
            abort(403, 'You do not have permission to submit this report.');
        }

        // CRITICAL: Verify all witness statements are received before allowing submission
        if (!$incident->allWitnessStatementsReceived()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'All witness statements must be received before submitting the HOD report.',
                ], 422);
            }
            return back()->with('error', 'All witness statements must be received before submitting the HOD report.');
        }

        $validated = $request->validate([
            'hod_report' => 'required|string|min:100|max:5000',
            'acknowledged_witness_statements' => 'required|accepted',
        ], [
            'hod_report.required' => 'Please provide the Head of Department report.',
            'hod_report.min' => 'Report must be at least 100 characters.',
            'hod_report.max' => 'Report cannot exceed 5000 characters.',
            'acknowledged_witness_statements.required' => 'You must acknowledge that you have read the witness statements.',
            'acknowledged_witness_statements.accepted' => 'You must acknowledge that you have read the witness statements.',
        ]);

        try {
            DB::beginTransaction();

            // Save HOD report temporarily (not yet escalated to HR)
            $incident->update([
                'hod_report' => $validated['hod_report'],
                'hod_report_submitted_at' => now(),
                'hod_report_submitted_by' => $user->id,
                'hod_acknowledged_witness_statements' => $validated['acknowledged_witness_statements'],
            ]);

            DB::commit();

            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Head of Department report has been saved. Please review the complete case dossier before final submission to HR.",
                    'redirect' => route('incidents.supervisor_dossier_review', ['incident' => $incident]),
                ], 200);
            }

            // Handle normal form submissions - redirect directly
            return redirect()->route('incidents.supervisor_dossier_review', ['incident' => $incident])
                           ->with('success', 'Head of Department report has been saved. Please review the complete case dossier before final submission to HR.');
                           
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('HOD report submission failed', [
                'incident_id' => $incident->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while submitting the Head of Department report. Please try again.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'An error occurred while submitting the Head of Department report. Please try again.');
        }
    }

    /**
     * Display supervisor dossier review page before final submission to HR.
     * Shows all incident details, first aid, and witness statements.
     */
    public function supervisorDossierReview(Incident $incident)
    {
        $user = auth()->user();

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'You are not the assigned supervisor for this incident.');
        }

        // Check if already escalated to HR
        if ($incident->escalated_to_hr_at) {
            return redirect()
                ->route('dashboards.supervisor')
                ->with('info', 'This case has already been submitted to HR on ' . $incident->escalated_to_hr_at->format('M d, Y H:i'));
        }

        // Ensure HOD report has been submitted
        if (!$incident->hod_report_submitted_at) {
            return redirect()
                ->route('incidents.hod_report', ['incident' => $incident])
                ->with('error', 'HOD report must be submitted first.');
        }

        // Get all relevant data for dossier display
        $firstAidRecords = $incident->firstAidRecords ?? collect();
        $witnessStatements = $incident->getSubmittedWitnessStatements();
        
        return view('incidents.supervisor_dossier_review', compact('incident', 'firstAidRecords', 'witnessStatements'));
    }

    /**
     * Submit the complete dossier to HR and escalate the incident.
     */
    public function submitDossierToHR(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'You are not the assigned supervisor for this incident.');
        }

        // Check if already escalated to HR (prevent duplicate submissions)
        if ($incident->escalated_to_hr_at) {
            return response()->json([
                'success' => false,
                'message' => 'This case has already been submitted to HR on ' . $incident->escalated_to_hr_at->format('M d, Y H:i'),
            ], 422);
        }

        // Ensure HOD report has been submitted
        if (!$incident->hod_report_submitted_at) {
            return response()->json([
                'success' => false,
                'message' => 'HOD report must be submitted first.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            \Log::info('Starting dossier submission', ['incident_id' => $incident->id]);

            // Determine HR routing destination based on facility type
            $routeType = $incident->determineReviewPool();
            \Log::info('Route type determined', ['incident_id' => $incident->id, 'route_type' => $routeType]);

            $hrGroupId = $this->getHRGroupForRouting($incident, $routeType);

            // Assign a specific HR user based on routing type
            $assignedHRUserId = $this->assignHRUserForIncident($incident, $routeType);
            \Log::info('HR user assigned', ['incident_id' => $incident->id, 'hr_user_id' => $assignedHRUserId]);

            // Update incident with final escalation status
            $incident->update([
                'status' => 'VERIFIED_SUPERVISOR',
                'hr_route_type' => $routeType,
                'hr_assigned_group_id' => $hrGroupId,
                'routed_to_hr_user_id' => $assignedHRUserId,
                'escalated_to_hr_at' => now(),
            ]);

            // Fire event to notify HR group of new case (Stage 5 trigger)
            try {
                event(new \App\Events\IncidentEscalatedToHR($incident, $hrGroupId, $routeType));
            } catch (\Exception $eventError) {
                \Log::error('Error firing IncidentEscalatedToHR event', [
                    'incident_id' => $incident->id,
                    'error' => $eventError->getMessage(),
                ]);
                // Don't fail the submission because of event error
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Case {$incident->reference_number} has been submitted to HR successfully.",
                    'redirect' => route('dashboards.supervisor'),
                ], 200);
            }

            return redirect()
                ->route('dashboards.supervisor')
                ->with('success', "Case {$incident->reference_number} has been submitted to HR successfully.");
        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Dossier submission to HR failed', [
                'incident_id' => $incident->id,
                'supervisor_id' => $user->id,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to submit to HR. Please try again.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to submit to HR. Please try again.');
        }
    }

    /**
     * Helper: Get the appropriate HR group ID based on facility type routing.
     * This implements the facility-type routing engine per HSB standards.
     */
    private function getHRGroupForRouting(Incident $incident, string $routeType): ?int
    {
        // TODO: Implement logic to fetch or create HR group based on:
        // - If DISTRICT_HR_REVIEW: Get the District HR group for the incident's district_id
        // - If HOSPITAL_HR_REVIEW: Get the Hospital HR group for the incident's facility_id
        // - If CENTRAL_HR_REVIEW: Get the Central HR Executive group (hardcoded or single record)
        
        // Placeholder implementation - return null (will be populated in Stage 5)
        return null;
    }

    /**
     * Assign a specific HR user to this incident based on routing type.
     * Uses round-robin load balancing to distribute cases evenly.
     */
    private function assignHRUserForIncident(Incident $incident, string $routeType): ?int
    {
        $hrRole = match($routeType) {
            'DISTRICT_HR_REVIEW' => 'district_hr',
            'RESIDENT_LOCAL_HR_REVIEW' => 'resident_local_hr',
            'HOSPITAL_HR_REVIEW' => 'hospital_hr',
            'CENTRAL_HR_REVIEW' => 'hospital_hr',
            default => null,
        };

        if (!$hrRole) {
            return null;
        }

        // Find all HR users with the appropriate role
        $hrUsers = \App\Models\User::whereHas('roles', function ($query) use ($hrRole) {
            $query->where('name', $hrRole);
        })->get();

        if ($hrUsers->isEmpty()) {
            Log::warning("No HR users found for role: {$hrRole}", [
                'incident_id' => $incident->id,
                'routing_type' => $routeType,
            ]);
            return null;
        }

        // If only one HR user, assign to them
        if ($hrUsers->count() === 1) {
            return $hrUsers->first()->id;
        }

        // Load balancing: Assign to HR user with fewest pending cases
        $hrUserWithLeastCases = $hrUsers->map(function ($hrUser) {
            $caseCount = \App\Models\Incident::where('routed_to_hr_user_id', $hrUser->id)
                ->whereIn('status', ['VERIFIED_SUPERVISOR', 'HR_RETURNED_FOR_CORRECTIONS'])
                ->count();
            
            return [
                'user_id' => $hrUser->id,
                'case_count' => $caseCount,
            ];
        })->sortBy('case_count')->first();

        return $hrUserWithLeastCases['user_id'] ?? null;
    }

    /**
     * Show pending incidents for HR review.
     */
    public function hrPendingReview()
    {
        $user = auth()->user();

        // Get pending incidents assigned to this specific HR user
        // The routed_to_hr_user_id is already assigned based on their role, so no need to filter by route type
        $incidents = \App\Models\Incident::where('status', 'VERIFIED_SUPERVISOR')
            ->where('routed_to_hr_user_id', $user->id)
            ->orderBy('escalated_to_hr_at', 'asc')
            ->get();

        return view('incidents.hr_pending_review', compact('incidents'));
    }

    /**
     * Show incident for HR review.
     */
    public function hrView(Incident $incident)
    {
        $user = auth()->user();

        // Verify that this incident is assigned to the logged-in HR user
        if ($incident->routed_to_hr_user_id !== $user->id) {
            abort(403, 'You do not have access to this incident.');
        }

        return view('incidents.hr_view', compact('incident'));
    }

    /**
     * Phase 1: Show case dossier with all prior workflow details.
     */
    public function hrCaseDossier(Incident $incident)
    {
        $user = auth()->user();

        // Verify that this incident is assigned to the logged-in HR user
        if ($incident->routed_to_hr_user_id !== $user->id) {
            abort(403, 'You do not have access to this incident.');
        }

        // Load attachments for document display
        $incident->load('attachments');

        return view('incidents.hr_case_dossier', compact('incident'));
    }

    /**
     * Phase 1: Acknowledge case dossier and proceed to document verification.
     */
    public function acknowledgeCaseDossier(Request $request, Incident $incident)
    {
        $user = auth()->user();

        try {
            $validated = $request->validate([
                'acknowledged' => 'required|in:1',
            ], [
                'acknowledged.required' => 'You must acknowledge receipt of the case file.',
                'acknowledged.in' => 'You must acknowledge receipt of the case file.',
            ]);

            $incident->update([
                'hr_dossier_acknowledged_at' => now(),
                'hr_dossier_acknowledged_by' => $user->id,
            ]);

            return redirect()->route('incidents.document_verification', ['incident' => $incident->id])->with('success', 'Case file acknowledged. Proceeding to document verification.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Case dossier acknowledgment failed', [
                'incident_id' => $incident->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while acknowledging the case file: ' . $e->getMessage());
        }
    }

    /**
     * Phase 2: Show document verification screen with mandatory and conditional documents.
     */
    public function hrDocumentVerification(Incident $incident)
    {
        $user = auth()->user();

        // Verify that this incident is assigned to the logged-in HR user
        if ($incident->routed_to_hr_user_id !== $user->id) {
            abort(403, 'You do not have access to this incident.');
        }

        // Define mandatory documents (always required) - must match document_type values in database
        $mandatoryDocuments = [
            'doctors_report' => "Doctor's Report",
            'injury_report' => 'Injury Report from Z1149',
            'sick_leave' => 'Sick Leave Forms',
            'treatment_records' => 'Certified Copies of Initial Treatment Records',
            'national_id' => 'Certified Copy of National Registration Card',
            'banking_details' => 'Banking Details Forms',
            'bank_statement' => 'Bank Statements (Nostro & ZiG)',
        ];

        // Define conditional documents
        $conditionalDocuments = [
            'death_certificate' => 'Death Certificate',
            'police_report' => 'Police Report',
            'heir_certificate' => 'Certificate of Heir/Letter of Administration/Certificate of Guardianship',
            'marriage_certificate' => 'Certified Copy of Marriage Certificate/Marriage Confirmation',
        ];

        // Determine which conditional documents become mandatory
        $mandatoryConditional = [];
        
        if ($incident->is_workplace_fatality) {
            // Fatality case requires death and heir documents
            $mandatoryConditional['death_certificate'] = $conditionalDocuments['death_certificate'];
            $mandatoryConditional['heir_certificate'] = $conditionalDocuments['heir_certificate'];
        }
        
        if ($incident->is_road_traffic_accident) {
            // Road accident requires police report
            $mandatoryConditional['police_report'] = $conditionalDocuments['police_report'];
        }

        // Optional documents (always optional)
        $optionalDocuments = [
            'medical_receipts' => 'Medical Receipts',
            'additional_evidence' => 'Additional Supporting Evidence',
        ];

        // Get incident attachments
        $attachments = $incident->attachments()->get() ?? [];

        return view('incidents.hr_document_verification_simple', compact(
            'incident',
            'mandatoryDocuments',
            'conditionalDocuments',
            'mandatoryConditional',
            'optionalDocuments',
            'attachments'
        ));
    }

    /**
     * View an attachment file in browser
     */
    public function viewAttachment(Incident $incident, IncidentAttachment $attachment)
    {
        $user = auth()->user();

        // Verify attachment belongs to this incident
        if ($attachment->incident_id !== $incident->id) {
            abort(403, 'Unauthorized');
        }

        // Check if user has permission to view this case
        // DMO, Medical Superintendent, CMO, PMD can view if assigned or reviewing
        if (in_array($user->roles()->first()?->name, ['dmo', 'medical_superintendent', 'cmo', 'pmd'])) {
            $canView = $incident->routed_to_dmo_user_id === $user->id ||
                       $incident->routed_to_pmd_user_id === $user->id;
            if (!$canView) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->hasRole('district_hr') || $user->hasRole('hospital_hr') || $user->hasRole('central_hr')) {
            // HR users can view if they're assigned
            $canView = $incident->routed_to_hr_user_id === $user->id;
            if (!$canView) {
                abort(403, 'Unauthorized');
            }
        } else {
            abort(403, 'Unauthorized');
        }

        // Check if file exists
        $filePath = storage_path('app/private/' . $attachment->file_path);
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // For all file types, return with inline disposition and proper MIME type
        $ext = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'txt' => 'text/plain',
        ];

        $mimeType = $mimeTypes[$ext] ?? $attachment->mime_type ?? 'application/octet-stream';

        // Read file and return as response
        $fileContent = file_get_contents($filePath);
        
        return response($fileContent)
            ->header('Content-Type', $mimeType)
            ->header('Content-Length', filesize($filePath))
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('Accept-Ranges', 'bytes');
    }

    /**
     * Show leave information capture page (after document verification).
     */
    public function leaveInformationCapture(Incident $incident)
    {
        $user = auth()->user();

        // Verify that this incident is assigned to the logged-in HR user
        // OR the user has manage_incidents permission
        if ($incident->routed_to_hr_user_id !== $user->id && !$user->hasPermission('manage_incidents')) {
            abort(403, 'You do not have access to this incident.');
        }

        // Load relationships
        $incident->load('leaveMilestones');

        return view('incidents.leave_information_capture', compact('incident'));
    }

    /**
     * Get witness statement data via API
     */
    public function getWitnessStatement($witnessTokenId)
    {
        $user = auth()->user();
        
        $witness = IncidentWitnessToken::where('id', $witnessTokenId)
            ->firstOrFail();

        // Verify user has permission to view incidents
        if (!$user->hasPermission('view_incidents')) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'witness_name' => $witness->witness_name,
            'statement' => $witness->statement,
            'submitted_at' => $witness->statement_submitted_at?->format('M d, Y H:i'),
        ]);
    }

    /**
     * Download witness statement as a text file (Task 5.4)
     * Route: GET /supervisor/incidents/{incident}/witness/{tokenId}/download
     * Requirements: 3.6
     */
    public function downloadWitnessStatement(Incident $incident, $tokenId)
    {
        $user = auth()->user();

        // Verify supervisor role
        if (!$user->hasRole('supervisor')) {
            abort(403, 'Access denied. You must have supervisor role to download statements.');
        }

        // Verify supervisor is assigned to this incident
        if ($incident->assigned_supervisor_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned supervisor for this incident.');
        }

        // Get the witness token
        $witnessToken = $incident->witnessTokens()->findOrFail($tokenId);

        // Verify witness has submitted a statement
        if (!$witnessToken->hasSubmitted()) {
            abort(404, 'This witness has not submitted a statement yet.');
        }

        // Generate filename with incident reference and witness name
        $filename = sprintf(
            '%s_witness_%s_%s.txt',
            $incident->reference_number,
            str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9\s]/', '', $witnessToken->witness_name)),
            now()->format('Y-m-d')
        );

        // Return statement as download
        return response()->streamDownload(function () use ($witnessToken) {
            echo $witnessToken->statement;
        }, $filename, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    /**
     * Get first aid record data via API
     */
    public function getFirstAidRecord($recordId)
    {
        $user = auth()->user();
        
        $record = FirstAidRecord::findOrFail($recordId);

        // Verify user has permission to view first aid records
        if (!$user->hasPermission('view_first_aid')) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'id' => $record->id,
            'nurse_name' => $record->nurse_name,
            'nurse_license_number' => $record->nurse_license_number,
            'time_administered' => $record->time_administered,
            'severity_level' => $record->severity_level,
            'injury_description' => $record->injury_description,
            'first_aid_description' => $record->first_aid_description,
            'notes' => $record->notes,
            'status' => $record->status,
            'created_at' => $record->created_at,
        ]);
    }

    /**
     * API endpoint to verify an attachment via JSON request
     */
    public function verifyAttachmentApi(Request $request)
    {
        $validated = $request->validate([
            'attachment_id' => 'required|integer|exists:incident_attachments,id',
            'incident_id' => 'required|integer|exists:incidents,id',
        ]);

        $user = auth()->user();
        $incident = Incident::findOrFail($validated['incident_id']);
        $attachment = IncidentAttachment::findOrFail($validated['attachment_id']);

        // Verify attachment belongs to this incident
        if ($attachment->incident_id !== $incident->id) {
            return response()->json(['success' => false, 'message' => 'Attachment does not belong to this incident'], 403);
        }

        try {
            $attachment->verify($user->id);

            Log::info('Document verified via API', [
                'incident_id' => $incident->id,
                'attachment_id' => $attachment->id,
                'verified_by' => $user->id,
            ]);

            return response()->json(['success' => true, 'message' => 'Document verified successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to verify document', [
                'incident_id' => $incident->id,
                'attachment_id' => $attachment->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to verify document'], 500);
        }
    }

    /**
     * API endpoint to reject an attachment via JSON request
     */
    public function rejectAttachmentApi(Request $request)
    {
        $validated = $request->validate([
            'attachment_id' => 'required|integer|exists:incident_attachments,id',
            'incident_id' => 'required|integer|exists:incidents,id',
            'reason' => 'required|string|min:5|max:500',
        ]);

        $user = auth()->user();
        $incident = Incident::findOrFail($validated['incident_id']);
        $attachment = IncidentAttachment::findOrFail($validated['attachment_id']);

        // Verify attachment belongs to this incident
        if ($attachment->incident_id !== $incident->id) {
            return response()->json(['success' => false, 'message' => 'Attachment does not belong to this incident'], 403);
        }

        try {
            $attachment->reject($user->id, $validated['reason']);

            Log::info('Document rejected via API', [
                'incident_id' => $incident->id,
                'attachment_id' => $attachment->id,
                'rejected_by' => $user->id,
                'reason' => $validated['reason'],
            ]);

            // Send SMS to reporter about the rejection
            $reporterPhone = $incident->phone_number ?? ($incident->employee?->phone_number ?? null);
            if ($reporterPhone && config('services.sms.enabled')) {
                $smsMessage = "HSC-OHS: Your document for case {$incident->reference_number} has been rejected.\n\n" .
                    "Reason: {$validated['reason']}\n\n" .
                    "Please correct the document and re-upload it via the track page.";

                try {
                    $smsService = app(SMSService::class);
                    $smsService->send($reporterPhone, $smsMessage);

                    Log::info('Rejection SMS sent to reporter', [
                        'incident_id' => $incident->id,
                        'phone' => $reporterPhone,
                        'document_type' => $attachment->document_type,
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to send rejection SMS to reporter', [
                        'incident_id' => $incident->id,
                        'error' => $e->getMessage(),
                    ]);
                    // Continue - don't fail the rejection if SMS fails
                }
            }

            return response()->json(['success' => true, 'message' => 'Document rejected successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to reject document', [
                'incident_id' => $incident->id,
                'attachment_id' => $attachment->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to reject document'], 500);
        }
    }



    /**
     * Download an attachment file
     */
    public function downloadAttachment(Incident $incident, IncidentAttachment $attachment)
    {
        $user = auth()->user();

        // Verify attachment belongs to this incident
        if ($attachment->incident_id !== $incident->id) {
            abort(403, 'Unauthorized');
        }

        // Check if user has permission to view this case
        // DMO, Medical Superintendent, CMO, PMD can view if assigned or reviewing
        if (in_array($user->roles()->first()?->name, ['dmo', 'medical_superintendent', 'cmo', 'pmd'])) {
            $canView = $incident->routed_to_dmo_user_id === $user->id ||
                       $incident->routed_to_pmd_user_id === $user->id;
            if (!$canView) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->hasRole('district_hr') || $user->hasRole('hospital_hr') || $user->hasRole('central_hr')) {
            // HR users can view if they're assigned
            $canView = $incident->routed_to_hr_user_id === $user->id;
            if (!$canView) {
                abort(403, 'Unauthorized');
            }
        } else {
            abort(403, 'Unauthorized');
        }

        // Check if file exists
        $filePath = storage_path('app/private/' . $attachment->file_path);
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Force download
        return response()->download($filePath, $attachment->file_name);
    }

    /**
     * Verify an uploaded document
     */
    public function verifyAttachment(Request $request, Incident $incident, IncidentAttachment $attachment)
    {
        $user = auth()->user();

        // Verify HR user has access to this incident
        if ($user->hasRole('district_hr') && $incident->hr_route_type !== 'DISTRICT_HR_REVIEW') {
            return redirect()->back()->with('error', 'Unauthorized access');
        } elseif ($user->hasRole('hospital_hr') && $incident->hr_route_type !== 'HOSPITAL_HR_REVIEW') {
            return redirect()->back()->with('error', 'Unauthorized access');
        } elseif ($user->hasRole('central_hr') && $incident->hr_route_type !== 'CENTRAL_HR_REVIEW') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Verify attachment belongs to this incident
        if ($attachment->incident_id !== $incident->id) {
            return redirect()->back()->with('error', 'Attachment does not belong to this incident');
        }

        // For sick leave forms, validate and store leave period data
        if (in_array($attachment->document_type, ['sick_leave', 'sick_leave_form'])) {
            $validated = $request->validate([
                'injury_leave_start_date' => 'required|date',
                'injury_leave_end_date' => 'required|date|after_or_equal:injury_leave_start_date',
                'total_injury_leave_days' => 'required|integer|min:1',
            ], [
                'injury_leave_start_date.required' => 'Injury leave start date is required for sick leave forms.',
                'injury_leave_end_date.required' => 'Injury leave end date is required for sick leave forms.',
                'injury_leave_end_date.after_or_equal' => 'End date must be on or after the start date.',
                'total_injury_leave_days.required' => 'Total leave days calculation is required.',
            ]);

            // Calculate and verify the days
            $startDate = \Carbon\Carbon::parse($validated['injury_leave_start_date']);
            $endDate = \Carbon\Carbon::parse($validated['injury_leave_end_date']);
            $calculatedDays = $startDate->diffInDays($endDate) + 1; // +1 to include both dates

            // Update incident with leave tracking data
            $incident->update([
                'injury_leave_start_date' => $validated['injury_leave_start_date'],
                'injury_leave_end_date' => $validated['injury_leave_end_date'],
                'total_injury_leave_days' => $calculatedDays,
            ]);

            Log::info('Injury leave period recorded', [
                'incident_id' => $incident->id,
                'start_date' => $validated['injury_leave_start_date'],
                'end_date' => $validated['injury_leave_end_date'],
                'total_days' => $calculatedDays,
            ]);
        }

        try {
            $attachment->verify($user->id);

            Log::info('Document verified', [
                'incident_id' => $incident->id,
                'attachment_id' => $attachment->id,
                'verified_by' => $user->id,
            ]);

            return redirect()->back()->with('success', 'Document verified successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to verify document', [
                'incident_id' => $incident->id,
                'attachment_id' => $attachment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to verify document');
        }
    }

    /**
     * Reject an uploaded document
     */
    public function rejectAttachment(Incident $incident, IncidentAttachment $attachment)
    {
        $user = auth()->user();

        // Verify HR user has access to this incident
        if ($user->hasRole('district_hr') && $incident->hr_route_type !== 'DISTRICT_HR_REVIEW') {
            return redirect()->back()->with('error', 'Unauthorized access');
        } elseif ($user->hasRole('hospital_hr') && $incident->hr_route_type !== 'HOSPITAL_HR_REVIEW') {
            return redirect()->back()->with('error', 'Unauthorized access');
        } elseif ($user->hasRole('central_hr') && $incident->hr_route_type !== 'CENTRAL_HR_REVIEW') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Verify attachment belongs to this incident
        if ($attachment->incident_id !== $incident->id) {
            return redirect()->back()->with('error', 'Attachment does not belong to this incident');
        }

        $reason = request()->input('reason', 'No reason provided');

        try {
            $attachment->reject($user->id, $reason);

            Log::info('Document rejected', [
                'incident_id' => $incident->id,
                'attachment_id' => $attachment->id,
                'rejected_by' => $user->id,
                'reason' => $reason,
            ]);

            return redirect()->back()->with('success', 'Document rejected successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to reject document', [
                'incident_id' => $incident->id,
                'attachment_id' => $attachment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to reject document');
        }
    }

    /**
     * Submit compliance review decision with conditional document validation.
     */
    public function submitComplianceReview(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify HR user has access based on their role and incident routing
        if ($user->hasRole('district_hr') && $incident->hr_route_type !== 'DISTRICT_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        } elseif ($user->hasRole('hospital_hr') && $incident->hr_route_type !== 'HOSPITAL_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        } elseif ($user->hasRole('central_hr') && $incident->hr_route_type !== 'CENTRAL_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        }

        try {
            $validated = $request->validate([
                'compliance_decisions' => 'required|array',
                'compliance_decisions.*.status' => 'required|in:ACCEPTED,REJECTED',
                'compliance_decisions.*.reason' => 'nullable|string|max:500',
                'optional_decisions' => 'nullable|array',
                'optional_decisions.*.status' => 'nullable|in:ACCEPTED,REJECTED',
                'optional_decisions.*.reason' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            $decisions = $validated['compliance_decisions'] ?? [];
            $optionalDecisions = $validated['optional_decisions'] ?? [];
            
            $allMandatoryAccepted = true;
            $rejectedReasons = [];
            $acceptedCount = 0;
            $totalMandatory = count($decisions);

            // Check mandatory documents compliance
            foreach ($decisions as $key => $decision) {
                if ($decision['status'] === 'REJECTED') {
                    $allMandatoryAccepted = false;
                    $rejectedReasons[$key] = $decision['reason'] ?? 'No reason provided';
                } elseif ($decision['status'] === 'ACCEPTED') {
                    $acceptedCount++;
                }
            }

            // If all mandatory documents are accepted, approve and escalate
            if ($allMandatoryAccepted && $acceptedCount === $totalMandatory) {
                // Determine escalation based on facility type and HR route type
                $facility = $incident->facility;
                $dmoId = null;
                $districtHrId = null;
                $medicalSuperId = null;
                $cmoId = null;
                $nextStatus = 'HR_REVIEW_COMPLETED';
                $escalationWarning = '';

                if ($facility) {
                    if ($incident->hr_route_type === 'RESIDENT_LOCAL_HR_REVIEW') {
                        // Resident Local HR level - escalate to District HR
                        $districtHr = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'district_hr'))
                            ->where('district_id', $facility->district_id)
                            ->first();
                        
                        if ($districtHr) {
                            $districtHrId = $districtHr->id;
                            $nextStatus = 'ESCALATED_TO_DISTRICT_HR';
                        }
                        
                    } elseif ($incident->hr_route_type === 'DISTRICT_HR_REVIEW') {
                        // District HR level - escalate to District Medical Officer
                        // IMPORTANT: Only escalate to DMO in the same district - NO FALLBACK
                        $dmo = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'dmo'))
                            ->where('district_id', $facility->district_id)
                            ->first();
                        
                        if ($dmo) {
                            $dmoId = $dmo->id;
                            $nextStatus = 'ESCALATED_TO_DMO';
                        } else {
                            // No DMO in this district - CANNOT ESCALATE AUTOMATICALLY
                            // Status remains HR_APPROVED and HR must manually escalate
                            $escalationWarning = 'ERROR: No District Medical Officer found for ' . $facility->district->name . 
                                ' (' . $facility->district_id . '). Case cannot be automatically escalated. ' .
                                'HR must contact the District Administrator for manual escalation.';
                            $nextStatus = 'HR_APPROVED'; // Keep case at HR level
                        }
                        
                    } elseif ($incident->hr_route_type === 'HOSPITAL_HR_REVIEW') {
                        // Hospital HR level - escalate to Medical Superintendent
                        $medicalSuper = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'medical_superintendent'))
                            ->where('facility_id', $facility->id)
                            ->first();
                        
                        if ($medicalSuper) {
                            $medicalSuperId = $medicalSuper->id;
                            $nextStatus = 'ESCALATED_TO_MEDICAL_SUPERINTENDENT';
                        }
                        
                    } elseif ($incident->hr_route_type === 'CENTRAL_HR_REVIEW') {
                        // Central HR level - escalate to Chief Medical Officer
                        $cmo = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'cmo'))
                            ->where('facility_id', $facility->id)
                            ->first();
                        
                        if ($cmo) {
                            $cmoId = $cmo->id;
                            $nextStatus = 'ESCALATED_TO_CMO';
                        }
                    }
                }

                $incident->update([
                    'status' => $nextStatus,
                    'hr_reviewed_at' => now(),
                    'hr_reviewed_by' => $user->id,
                    'hr_compliance_status' => 'APPROVED',
                    'compliance_status' => 'COMPLIANT',
                    'hr_review_completed_at' => now(),
                    'hr_review_completed_by_user_id' => $user->id,
                    'routed_to_dmo_user_id' => $dmoId,
                    'routed_to_district_hr_user_id' => $districtHrId,
                    'routed_to_medical_superintendent_user_id' => $medicalSuperId,
                    'routed_to_cmo_user_id' => $cmoId,
                ]);

                // Log the approval
                Log::info('Incident compliance approved and escalated', [
                    'incident_id' => $incident->id,
                    'reference_number' => $incident->reference_number,
                    'hr_officer_id' => $user->id,
                    'hr_route_type' => $incident->hr_route_type,
                    'next_status' => $nextStatus,
                    'dmo_id' => $dmoId,
                    'timestamp' => now(),
                ]);

                DB::commit();

                // Check if escalation was successful or blocked
                if ($nextStatus === 'HR_APPROVED' && $escalationWarning) {
                    // Escalation failed - case stays at HR_APPROVED and needs manual escalation
                    Log::warning('Incident escalation blocked - manual escalation required', [
                        'incident_id' => $incident->id,
                        'reference_number' => $incident->reference_number,
                        'reason' => $escalationWarning,
                        'hr_officer_id' => $user->id,
                        'timestamp' => now(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => $escalationWarning,
                        'blocked' => true,
                        'redirect' => route('incidents.hr_pending_review')
                    ], 400);
                }

                // Escalation successful
                Log::info('Incident compliance approved and escalated', [
                    'incident_id' => $incident->id,
                    'reference_number' => $incident->reference_number,
                    'hr_officer_id' => $user->id,
                    'hr_route_type' => $incident->hr_route_type,
                    'next_status' => $nextStatus,
                    'dmo_id' => $dmoId,
                    'timestamp' => now(),
                ]);

                // Always return JSON for this endpoint
                return response()->json([
                    'success' => true,
                    'message' => "Incident {$incident->reference_number} has been approved and escalated successfully.",
                    'redirect' => route('incidents.hr_pending_review')
                ]);
            } else {
                // At least one mandatory document rejected - send back for corrections
                $incident->update([
                    'status' => 'HR_RETURNED_FOR_CORRECTIONS',
                    'hr_reviewed_at' => now(),
                    'hr_reviewed_by' => $user->id,
                    'hr_compliance_status' => 'REJECTED',
                    'hr_rejection_reasons' => json_encode($rejectedReasons),
                ]);

                // Log the rejection
                Log::warning('Incident returned for corrections', [
                    'incident_id' => $incident->id,
                    'reference_number' => $incident->reference_number,
                    'hr_officer_id' => $user->id,
                    'rejected_documents' => array_keys($rejectedReasons),
                    'timestamp' => now(),
                ]);

                DB::commit();

                // Always return JSON for this endpoint
                return response()->json([
                    'success' => false,
                    'message' => "Incident {$incident->reference_number} has been returned for corrections."
                ], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Compliance review submission failed', [
                'incident_id' => $incident->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the compliance review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the Administrative Dispatch Interface (final stage before submission to Social Services).
     */
    public function administrativeDispatch(Incident $incident)
    {
        $user = auth()->user();

        // Verify HR user has access to this incident
        if ($user->hasRole('district_hr') && $incident->hr_route_type !== 'DISTRICT_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        } elseif ($user->hasRole('hospital_hr') && $incident->hr_route_type !== 'HOSPITAL_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        } elseif ($user->hasRole('central_hr') && $incident->hr_route_type !== 'CENTRAL_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        }

        return view('incidents.administrative_dispatch', compact('incident'));
    }

    /**
     * Save HR Compliance Notes (minimum 50 characters).
     */
    public function saveComplianceNotes(Request $request, Incident $incident)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'hr_compliance_notes' => 'required|string|min:50|max:2000',
        ], [
            'hr_compliance_notes.required' => 'Compliance notes are required.',
            'hr_compliance_notes.min' => 'Compliance notes must be at least 50 characters.',
            'hr_compliance_notes.max' => 'Compliance notes cannot exceed 2000 characters.',
        ]);

        try {
            $incident->update([
                'hr_compliance_notes' => $validated['hr_compliance_notes'],
                'hr_compliance_notes_saved_at' => now(),
            ]);

            Log::info('HR compliance notes saved', [
                'incident_id' => $incident->id,
                'user_id' => $user->id,
                'character_count' => strlen($validated['hr_compliance_notes']),
            ]);

            return back()->with('success', 'Compliance notes saved successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to save compliance notes', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while saving compliance notes.');
        }
    }

    /**
     * Upload signed and stamped delivery manifest from Social Services.
     */

    /**
     * Finalize submission to Social Services (update status to SUBMITTED_TO_SOCIAL_SERVICES).
     */
    public function finalizeSubmission(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify all required fields are completed
        if (!$incident->hr_compliance_notes) {
            return back()->with('error', 'Compliance notes must be completed before finalizing.');
        }

        try {
            DB::beginTransaction();

            // Determine next escalation based on facility type and current HR level
            $facility = $incident->facility;
            $dmoId = null;
            $medicalSuperId = null;
            $cmoId = null;
            $nextStatus = 'HR_REVIEW_COMPLETED';
            $escalationWarning = '';

            if ($facility) {
                $facilityType = $facility->facilityType;
                
                // Escalation logic based on facility tier and HR route type
                if ($incident->hr_route_type === 'RESIDENT_LOCAL_HR_REVIEW') {
                    // Resident Local HR level (District Hospital) - escalate to District HR
                    $districtHr = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'district_hr'))
                        ->where('district_id', $facility->district_id)
                        ->first();
                    
                    if ($districtHr) {
                        $districtHrId = $districtHr->id;
                        $nextStatus = 'ESCALATED_TO_DISTRICT_HR';
                    } else {
                        $escalationWarning = ' WARNING: No District HR found for this district.';
                    }
                    
                } elseif ($incident->hr_route_type === 'DISTRICT_HR_REVIEW') {
                    // District HR level - escalate to District Medical Officer
                    // IMPORTANT: Only escalate to DMO in the same district - NO FALLBACK
                    $dmo = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'dmo'))
                        ->where('district_id', $facility->district_id)
                        ->first();
                    
                    if ($dmo) {
                        $dmoId = $dmo->id;
                        $nextStatus = 'ESCALATED_TO_DMO';
                    } else {
                        // No DMO in this district - CANNOT ESCALATE AUTOMATICALLY
                        $escalationWarning = 'ERROR: No District Medical Officer found for ' . $facility->district->name . 
                            '. Case cannot be automatically escalated. Manual escalation required.';
                    }
                    
                } elseif ($incident->hr_route_type === 'HOSPITAL_HR_REVIEW') {
                    // Hospital HR level - escalate to Medical Superintendent
                    $medicalSuper = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'medical_superintendent'))
                        ->where('facility_id', $facility->id)
                        ->first();
                    
                    if ($medicalSuper) {
                        $medicalSuperId = $medicalSuper->id;
                        $nextStatus = 'ESCALATED_TO_MEDICAL_SUPERINTENDENT';
                    } else {
                        $escalationWarning = ' WARNING: No Medical Superintendent found for this facility.';
                    }
                    
                } elseif ($incident->hr_route_type === 'CENTRAL_HR_REVIEW') {
                    // Central HR level - escalate to Chief Medical Officer (facility-bound)
                    $cmo = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'cmo'))
                        ->where('facility_id', $facility->id)
                        ->first();
                    
                    if ($cmo) {
                        $cmoId = $cmo->id;
                        $nextStatus = 'ESCALATED_TO_CMO';
                    } else {
                        $escalationWarning = ' WARNING: No CMO found for this facility.';
                    }
                }
            }

            $incident->update([
                'status' => $nextStatus,
                'hr_review_completed_at' => now(),
                'hr_review_completed_by_user_id' => $user->id,
                'routed_to_dmo_user_id' => $dmoId,
                'routed_to_district_hr_user_id' => $districtHrId,
                'routed_to_medical_superintendent_user_id' => $medicalSuperId,
                'routed_to_cmo_user_id' => $cmoId,
            ]);

            DB::commit();

            $escalationMessage = 'HR review is complete.';
            if ($nextStatus === 'ESCALATED_TO_DISTRICT_HR') {
                $escalationMessage .= ' Case has been escalated to District HR for further review.';
            } elseif ($nextStatus === 'ESCALATED_TO_DMO') {
                $escalationMessage .= ' Case has been escalated to the District Medical Officer for medical assessment.';
            } elseif ($nextStatus === 'ESCALATED_TO_MEDICAL_SUPERINTENDENT') {
                $escalationMessage .= ' Case has been escalated to the Medical Superintendent for medical assessment.';
            } elseif ($nextStatus === 'ESCALATED_TO_CMO') {
                $escalationMessage .= ' Case has been escalated to the Chief Medical Officer (facility-bound) for final assessment.';
            } else {
                $escalationMessage .= ' No escalation destination available at this time.';
            }

            Log::info('HR review finalized and escalated to executive officers', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'hr_officer_id' => $user->id,
                'hr_route_type' => $incident->hr_route_type,
                'facility_id' => $facility->id ?? null,
                'district_id' => $facility->district_id ?? null,
                'next_status' => $nextStatus,
                'dmo_id' => $dmoId,
                'medical_superintendent_id' => $medicalSuperId,
                'cmo_id' => $cmoId,
                'warning' => $escalationWarning,
                'submitted_at' => now(),
            ]);

            return redirect()->route('incidents.hr_pending_review')
                ->with('success', "Case {$incident->reference_number} has been finalized. {$escalationMessage}{$escalationWarning}");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to finalize submission', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while finalizing the submission: ' . $e->getMessage());
        }
    }

    /**
     * Download complete case dossier as PDF containing all documents and information.
     */
    public function downloadCaseDossier(Incident $incident)
    {
        $user = auth()->user();

        // Verify HR user has access to this incident
        if ($user->hasRole('district_hr') && $incident->hr_route_type !== 'DISTRICT_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        } elseif ($user->hasRole('hospital_hr') && $incident->hr_route_type !== 'HOSPITAL_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        } elseif ($user->hasRole('central_hr') && $incident->hr_route_type !== 'CENTRAL_HR_REVIEW') {
            abort(403, 'You do not have access to this incident.');
        }

        // Get all incident attachments
        $attachments = $incident->attachments()->get();

        // Build HTML for PDF
        $html = view('incidents.pdf.case_dossier', compact('incident', 'attachments'))->render();

        // Generate PDF
        $pdf = \PDF::loadHTML($html)
            ->setPaper('a4')
            ->setOption('margin-top', 0.5)
            ->setOption('margin-bottom', 0.5)
            ->setOption('margin-left', 0.5)
            ->setOption('margin-right', 0.5);

        // Download file
        $workerName = str_replace(' ', '_', $incident->first_name . '_' . $incident->surname);
        return $pdf->download($workerName . '_Case_' . $incident->reference_number . '_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Update incident flags (workplace fatality, road traffic accident).
     */
    public function updateIncidentFlags(Request $request, Incident $incident)
    {
        $user = auth()->user();

        // Verify HR user has access
        if (!$user->hasAnyRole(['district_hr', 'hospital_hr', 'central_hr'])) {
            abort(403, 'You do not have access to update incident flags.');
        }

        $validated = $request->validate([
            'is_workplace_fatality' => 'boolean',
            'is_road_traffic_accident' => 'boolean',
        ]);

        try {
            $incident->update($validated);

            Log::info('Incident flags updated', [
                'incident_id' => $incident->id,
                'is_workplace_fatality' => $validated['is_workplace_fatality'] ?? false,
                'is_road_traffic_accident' => $validated['is_road_traffic_accident'] ?? false,
                'updated_by' => $user->id,
            ]);

            return back()->with('success', 'Incident flags updated. Required documents have been adjusted accordingly.');
        } catch (\Exception $e) {
            Log::error('Failed to update incident flags', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred while updating incident flags.');
        }
    }

    public function sendSmsToReporter(Request $request)
    {
        $user = auth()->user();

        // Verify HR user has access
        if (!$user->hasAnyRole(['district_hr', 'hospital_hr', 'central_hr'])) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to send SMS.'
            ], 403);
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:5000',
            'incident_id' => 'required|exists:incidents,id',
        ]);

        try {
            $incident = Incident::find($validated['incident_id']);

            // Initialize SMS service
            $smsService = new \App\Services\SMSService();

            // Send the SMS
            $smsService->send($validated['phone'], $validated['message']);

            Log::info('SMS sent to reporter', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'phone' => $validated['phone'],
                'sent_by' => $user->id,
                'timestamp' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SMS sent successfully to ' . $validated['phone'],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to send SMS to reporter', [
                'incident_id' => $validated['incident_id'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'error' => $e->getMessage(),
                'sent_by' => $user->id,
            ]);

            // Task 10.4: Don't expose SMS service details to UI - return generic error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending SMS. Please try again or contact support.',
            ], 500);
        }
    }

    public function generateSupervisorDossierPDF(Incident $incident)
    {
        try {
            // Get related data
            $firstAidRecords = $incident->firstAidRecords()->get();
            $witnessStatements = $incident->witnessStatements()->whereNotNull('statement_submitted_at')->get();

            // Create PDF instance
            $pdf = \PDF::loadView('incidents.pdf.supervisor_dossier_cover', compact('incident', 'firstAidRecords', 'witnessStatements'))
                ->setPaper('a4')
                ->setOption('margin-top', 10)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 10)
                ->setOption('margin-right', 10);

            // Generate filename
            $workerName = str_replace(' ', '_', $incident->first_name . '_' . $incident->surname);
            $filename = $workerName . '_Dossier_' . $incident->reference_number . '.pdf';

            // Download PDF directly
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Failed to generate supervisor dossier PDF', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint to fetch case dossier for modal display
     */
    public function supervisorDossierApi(Incident $incident)
    {
        $user = auth()->user();

        // Verify that the logged-in user is the assigned supervisor
        if ($incident->assigned_supervisor_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Load all incident relationships
        $incident->load([
            'designation',
            'department',
            'facility.district.province',
            'facilityType',
            'incidentType',
            'attachments',
            'firstAidRecords',
            'witnessTokens',
            'assignedSupervisor'
        ]);

        // Extract witness statements from witness tokens
        $witnessStatements = [];
        foreach ($incident->witnessTokens as $token) {
            if (!empty($token->statement)) {
                $witnessStatements[] = [
                    'id' => $token->id,
                    'witness_name' => $token->witness_name,
                    'statement' => $token->statement,
                    'submitted_at' => $token->statement_submitted_at,
                ];
            }
        }

        return response()->json([
            'incident' => [
                'id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'created_at' => $incident->created_at,
                'status' => $incident->status,
                'first_name' => $incident->first_name,
                'surname' => $incident->surname,
                'incident_date' => $incident->incident_date,
                'incident_description' => $incident->incident_description,
                'department' => $incident->department ? [
                    'id' => $incident->department->id,
                    'name' => $incident->department->name
                ] : null,
                'designation' => $incident->designation ? [
                    'id' => $incident->designation->id,
                    'name' => $incident->designation->name
                ] : null,
                'facility' => $incident->facility ? [
                    'id' => $incident->facility->id,
                    'name' => $incident->facility->name,
                    'district' => $incident->facility->district ? [
                        'id' => $incident->facility->district->id,
                        'name' => $incident->facility->district->name,
                        'province' => $incident->facility->district->province ? [
                            'id' => $incident->facility->district->province->id,
                            'name' => $incident->facility->district->province->name
                        ] : null
                    ] : null
                ] : null,
                'incident_type' => $incident->incidentType ? [
                    'id' => $incident->incidentType->id,
                    'name' => $incident->incidentType->name
                ] : null,
            ],
            'supervisor' => [
                'name' => $incident->supervisor_name,
                'designation_id' => $incident->supervisor_designation_id,
                'designation' => $incident->supervisor_designation_id ? ($incident->supervisorDesignation ? $incident->supervisorDesignation->name : 'N/A') : 'N/A',
                'witness_statement' => $incident->supervisor_witness_statement,
                'verified_at' => $incident->supervisor_verified_at,
                'verified_by' => $incident->assignedSupervisor ? ($incident->assignedSupervisor->first_name . ' ' . $incident->assignedSupervisor->surname) : 'N/A',
                'hod_report' => $incident->hod_report,
                'hod_report_submitted_at' => $incident->hod_report_submitted_at,
            ],
            'first_aid_records' => $incident->firstAidRecords->map(function($record) {
                return [
                    'id' => $record->id,
                    'nurse_name' => $record->nurse_name,
                    'nurse_license_number' => $record->nurse_license_number,
                    'injury_description' => $record->injury_description,
                    'first_aid_description' => $record->first_aid_description,
                    'time_administered' => $record->time_administered,
                    'location' => $record->location,
                    'severity_level' => $record->severity_level,
                    'notes' => $record->notes,
                    'created_at' => $record->created_at,
                ];
            }),
            'witness_statements' => $witnessStatements,
            'attachments' => $incident->attachments->map(function($attachment) {
                return [
                    'id' => $attachment->id,
                    'file_name' => $attachment->file_name,
                    'file_size' => $attachment->file_size,
                    'document_type' => $attachment->document_type,
                    'created_at' => $attachment->created_at,
                ];
            }),
        ]);
    }



}