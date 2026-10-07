<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class ReturnToWorkController extends Controller
{
    /**
     * Show RTW plan form for an incident
     */
    public function edit($incidentId)
    {
        $incident = Incident::findOrFail($incidentId);
        
        // Check authorization - only HR users can manage RTW
        if (!auth()->user()->can('manage-rtw', $incident)) {
            abort(403, 'Unauthorized to manage RTW for this incident');
        }
        
        return view('hr.rtw.edit', compact('incident'));
    }

    /**
     * Save/update RTW plan
     */
    public function update(Request $request, $incidentId)
    {
        $incident = Incident::findOrFail($incidentId);
        
        if (!auth()->user()->can('manage-rtw', $incident)) {
            abort(403, 'Unauthorized to manage RTW for this incident');
        }

        $validated = $request->validate([
            'rtw_start_date' => 'required|date|after_or_equal:today',
            'rtw_end_date' => 'required|date|after:rtw_start_date',
            'rtw_status' => 'required|in:not_started,active,on_track,requires_intervention,completed,terminated',
            'rtw_medical_restrictions' => 'required|array|min:1',
            'rtw_medical_restrictions.*' => 'string|max:255',
            'rtw_modified_duties' => 'required|array|min:1',
            'rtw_modified_duties.*' => 'string|max:255',
            'rtw_schedule' => 'required|array|min:1',
            'rtw_schedule.*.period' => 'required|string',
            'rtw_schedule.*.hours' => 'required|string',
            'rtw_accommodations' => 'required|string|max:1000',
            'rtw_last_review_date' => 'nullable|date|before_or_equal:today',
            'rtw_next_review_date' => 'required|date|after_or_equal:today',
            'rtw_doctor_clearance_received' => 'boolean',
            'rtw_plan_signed_off' => 'boolean',
            'rtw_review_notes' => 'nullable|string|max:2000',
        ]);

        // Format schedule into key-value pairs
        $schedule = [];
        if (isset($validated['rtw_schedule']) && is_array($validated['rtw_schedule'])) {
            foreach ($validated['rtw_schedule'] as $item) {
                if (!empty($item['period']) && !empty($item['hours'])) {
                    $schedule[$item['period']] = $item['hours'];
                }
            }
        }

        $incident->update([
            'rtw_start_date' => $validated['rtw_start_date'],
            'rtw_end_date' => $validated['rtw_end_date'],
            'rtw_status' => $validated['rtw_status'],
            'rtw_medical_restrictions' => json_encode($validated['rtw_medical_restrictions']),
            'rtw_modified_duties' => json_encode($validated['rtw_modified_duties']),
            'rtw_schedule' => json_encode($schedule),
            'rtw_accommodations' => $validated['rtw_accommodations'],
            'rtw_last_review_date' => $validated['rtw_last_review_date'],
            'rtw_next_review_date' => $validated['rtw_next_review_date'],
            'rtw_doctor_clearance_received' => $validated['rtw_doctor_clearance_received'] ?? false,
            'rtw_plan_signed_off' => $validated['rtw_plan_signed_off'] ?? false,
            'rtw_review_notes' => $validated['rtw_review_notes'],
        ]);

        return redirect()->route('hr.incidents.show', $incident->id)
            ->with('success', 'Return-to-work plan updated successfully');
    }

    /**
     * Create new RTW plan (from incident details page)
     */
    public function create($incidentId)
    {
        $incident = Incident::findOrFail($incidentId);
        
        if (!auth()->user()->can('manage-rtw', $incident)) {
            abort(403, 'Unauthorized to manage RTW for this incident');
        }

        // Pre-populate with default dates
        $incident->rtw_start_date = $incident->rtw_start_date ?? now();
        $incident->rtw_end_date = $incident->rtw_end_date ?? now()->addWeeks(4);
        $incident->rtw_status = $incident->rtw_status ?? 'not_started';

        return view('hr.rtw.create', compact('incident'));
    }

    /**
     * Mark RTW as reviewed (quick action)
     */
    public function markReviewed(Request $request, $incidentId)
    {
        $incident = Incident::findOrFail($incidentId);
        
        if (!auth()->user()->can('manage-rtw', $incident)) {
            abort(403, 'Unauthorized to manage RTW for this incident');
        }

        $validated = $request->validate([
            'rtw_review_notes' => 'required|string|max:2000',
            'rtw_next_review_date' => 'required|date|after_or_equal:today',
            'rtw_status' => 'required|in:on_track,requires_intervention',
        ]);

        $incident->update([
            'rtw_last_review_date' => now()->toDateString(),
            'rtw_next_review_date' => $validated['rtw_next_review_date'],
            'rtw_review_notes' => $validated['rtw_review_notes'],
            'rtw_status' => $validated['rtw_status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'RTW review recorded successfully'
        ]);
    }

    /**
     * Complete RTW plan
     */
    public function complete($incidentId)
    {
        $incident = Incident::findOrFail($incidentId);
        
        if (!auth()->user()->can('manage-rtw', $incident)) {
            abort(403, 'Unauthorized to manage RTW for this incident');
        }

        $incident->update([
            'rtw_status' => 'completed',
            'rtw_last_review_date' => now()->toDateString(),
        ]);

        return redirect()->back()
            ->with('success', 'Return-to-work plan marked as completed');
    }

    /**
     * Terminate RTW plan
     */
    public function terminate(Request $request, $incidentId)
    {
        $incident = Incident::findOrFail($incidentId);
        
        if (!auth()->user()->can('manage-rtw', $incident)) {
            abort(403, 'Unauthorized to manage RTW for this incident');
        }

        $validated = $request->validate([
            'rtw_review_notes' => 'required|string|max:500',
        ]);

        $incident->update([
            'rtw_status' => 'terminated',
            'rtw_review_notes' => $validated['rtw_review_notes'],
        ]);

        return redirect()->back()
            ->with('success', 'Return-to-work plan terminated');
    }
}
