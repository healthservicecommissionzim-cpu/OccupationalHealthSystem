<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MedicalBoardController extends Controller
{
    /**
     * Display the medical board dashboard with flagged cases.
     */
    public function dashboard(Request $request)
    {
        $query = Incident::whereIn('status', ['REFERRED_TO_MEDICAL_BOARD', 'MEDICAL_DISCHARGE_ELIGIBLE'])
            ->with(['leaveMilestones', 'facility', 'district']);
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference_number', 'like', "%{$searchTerm}%")
                  ->orWhere('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('surname', 'like', "%{$searchTerm}%");
            });
        }
        
        // Get paginated results
        $cases = $query->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $stats = [
            'pending_90_day' => Incident::where('status', 'REFERRED_TO_MEDICAL_BOARD')->count(),
            'pending_discharge' => Incident::where('status', 'MEDICAL_DISCHARGE_ELIGIBLE')->count(),
            'total_pending' => Incident::whereIn('status', ['REFERRED_TO_MEDICAL_BOARD', 'MEDICAL_DISCHARGE_ELIGIBLE'])->count(),
        ];
        
        return view('medical-board.dashboard', compact('cases', 'stats'));
    }

    /**
     * Display the case review detail view.
     */
    public function review(Incident $incident)
    {
        // Verify case is flagged
        if (!in_array($incident->status, ['REFERRED_TO_MEDICAL_BOARD', 'MEDICAL_DISCHARGE_ELIGIBLE'])) {
            abort(404, 'This case is not available for medical board review');
        }
        
        $incident->load(['leaveMilestones', 'facility', 'district', 'leaveInformationSavedBy']);
        
        return view('medical-board.review', compact('incident'));
    }

    /**
     * Submit a medical board decision.
     */
    public function submitDecision(Incident $incident, Request $request)
    {
        // Verify case is flagged
        if (!in_array($incident->status, ['REFERRED_TO_MEDICAL_BOARD', 'MEDICAL_DISCHARGE_ELIGIBLE'])) {
            return response()->json(['error' => 'This case is not available for decision'], 422);
        }
        
        $validated = $request->validate([
            'decision' => 'required|in:APPROVE_RETURN_TO_WORK,RECOMMEND_EXTENDED_LEAVE,RECOMMEND_MEDICAL_DISCHARGE',
            'decision_reason' => 'required|string|min:10',
        ]);
        
        try {
            // Store old status for audit
            $oldStatus = $incident->status;
            
            // Update incident status based on decision
            $newStatus = match($validated['decision']) {
                'APPROVE_RETURN_TO_WORK' => 'CLEARED_FOR_RETURN',
                'RECOMMEND_EXTENDED_LEAVE' => 'EXTENDED_LEAVE_APPROVED',
                'RECOMMEND_MEDICAL_DISCHARGE' => 'MEDICAL_DISCHARGE_RECOMMENDED',
            };
            
            $incident->update(['status' => $newStatus]);
            
            // Create audit log
            AuditLog::create([
                'incident_id' => $incident->id,
                'action' => 'medical_board_decision',
                'changed_by_user_id' => auth()->id(),
                'old_values' => json_encode(['status' => $oldStatus]),
                'new_values' => json_encode(['status' => $newStatus]),
                'metadata' => json_encode([
                    'decision' => $validated['decision'],
                    'decision_reason' => $validated['decision_reason'],
                    'decided_by' => auth()->user()->name,
                ]),
            ]);
            
            Log::info('Medical board decision recorded', [
                'incident_id' => $incident->id,
                'reference_number' => $incident->reference_number,
                'decision' => $validated['decision'],
                'decided_by' => auth()->id(),
            ]);
            
            return response()->json([
                'message' => 'Medical board decision recorded successfully',
                'incident' => [
                    'id' => $incident->id,
                    'reference_number' => $incident->reference_number,
                    'status' => $newStatus,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording medical board decision', [
                'incident_id' => $incident->id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'error' => 'Failed to record decision: ' . $e->getMessage()
            ], 500);
        }
    }
}
