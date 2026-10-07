<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Services\LeaveManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LeaveInformationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the leave information form for an incident.
     */
    public function show(Incident $incident)
    {
        // Check if incident is in appropriate status for leave capture
        if (!in_array($incident->status, ['VERIFIED_SUPERVISOR', 'Under_HR_Review', 'REFERRED_TO_MEDICAL_BOARD', 'MEDICAL_DISCHARGE_ELIGIBLE'])) {
            return response()->json([
                'error' => 'Case must be in HR review or later stages to capture leave information'
            ], 422);
        }
        
        return response()->json([
            'incident_id' => $incident->id,
            'reference_number' => $incident->reference_number,
            'worker_name' => "{$incident->first_name} {$incident->surname}",
            'incident_date' => $incident->incident_date?->format('Y-m-d'),
            'sick_leave_start_date' => $incident->sick_leave_start_date?->format('Y-m-d'),
            'medical_certificate_provided' => $incident->medical_certificate_provided,
            'expected_recovery_date' => $incident->expected_recovery_date?->format('Y-m-d'),
            'leave_payment_stage' => $incident->leave_payment_stage,
            'leave_status' => $incident->leave_status,
            'days_elapsed' => $incident->days_elapsed,
            'recovery_days_remaining' => $incident->recovery_days_remaining,
            'leave_information_saved_at' => $incident->leave_information_saved_at?->format('Y-m-d H:i'),
            'saved_by_user' => $incident->leaveInformationSavedBy?->name,
            'leave_milestones' => $incident->leaveMilestones()->get(['milestone_type', 'days_elapsed_at_flag', 'applied_at']),
        ]);
    }

    /**
     * Save leave information for an incident.
     */
    public function store(Incident $incident, Request $request, LeaveManagementService $leaveService)
    {
        // Validate input
        $validated = $request->validate([
            'sick_leave_start_date' => 'required|date_format:Y-m-d',
            'medical_certificate_provided' => 'nullable|in:0,1',
            'expected_recovery_date' => 'nullable|date_format:Y-m-d',
        ]);
        
        // Convert string boolean to actual boolean
        $validated['medical_certificate_provided'] = (bool) $validated['medical_certificate_provided'];
        
        try {
            // Save leave information via service
            $leaveService->saveLeaveInformation($incident, $validated, auth()->user());
            
            Log::info('Leave information saved via API', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'user_id' => auth()->id(),
            ]);
            
            return response()->json([
                'message' => 'Leave information saved successfully',
                'incident' => [
                    'id' => $incident->id,
                    'reference_number' => $incident->reference_number,
                    'days_elapsed' => $incident->days_elapsed,
                    'leave_payment_stage' => $incident->leave_payment_stage,
                    'leave_information_saved_at' => $incident->leave_information_saved_at?->format('Y-m-d H:i'),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Leave information validation failed', [
                'incident_id' => $incident->id,
                'errors' => $e->errors(),
            ]);
            
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving leave information', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'error' => 'Failed to save leave information: ' . $e->getMessage()
            ], 500);
        }
    }
}
