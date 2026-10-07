<?php

namespace App\Http\Controllers\CoS;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Services\CaseManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseFlowTrackerController extends Controller
{
    protected $caseService;

    public function __construct(CaseManagementService $caseService)
    {
        $this->caseService = $caseService;
    }

    /**
     * Show case flow tracker with timeline visualization.
     */
    public function show($caseId)
    {
        $this->authorize('view', 'timeline');

        $case = $this->caseService->getCaseWithTimeline($caseId);
        $timeline = $this->caseService->getCaseTimeline($caseId);
        $currentHolder = $this->caseService->getCurrentHolder($caseId);
        $isOverdue = $this->caseService->isCaseOverdue($caseId);

        // Log this access for audit trail
        app(\App\Services\AuditService::class)->logAction('accessed_timeline', $caseId, [
            'action' => 'Case timeline viewed',
            'affected_entity' => 'Case Timeline',
        ]);

        return view('dashboards.cos.case-flow.show', compact(
            'case',
            'timeline',
            'currentHolder',
            'isOverdue'
        ));
    }

    /**
     * Add a monitoring note to a case.
     */
    public function addMonitoringNote(Request $request, $caseId)
    {
        $this->authorize('add', 'monitoring_note');

        $validated = $request->validate([
            'content' => 'required|string|min:10|max:2000',
        ]);

        try {
            $case = Incident::findOrFail($caseId);

            // Create monitoring note
            $note = $case->monitoringNotes()->create([
                'author_id' => Auth::id(),
                'content' => $validated['content'],
            ]);

            // Log to audit trail
            app(\App\Services\AuditService::class)->logAction('monitoring_note_added', $caseId, [
                'action' => 'Monitoring note added',
                'affected_entity' => 'Monitoring Note',
                'new_values' => ['note_id' => $note->id],
            ]);

            return back()->with('success', 'Monitoring note added successfully');
        } catch (\Exception $e) {
            return back()->withError('Failed to add monitoring note: ' . $e->getMessage());
        }
    }

    /**
     * Send a nudge reminder to the current case holder.
     */
    public function sendNudge(Request $request, $caseId)
    {
        $this->authorize('send', 'reminder');

        try {
            $case = Incident::findOrFail($caseId);

            $success = $this->caseService->sendNudgeToHolder($caseId);

            if ($success) {
                return back()->with('success', 'Nudge reminder sent to case holder');
            } else {
                return back()->withError('No current holder assigned for this case');
            }
        } catch (\Exception $e) {
            return back()->withError('Failed to send nudge: ' . $e->getMessage());
        }
    }

    /**
     * Escalate a case to the next stage.
     */
    public function escalate(Request $request, $caseId)
    {
        $this->authorize('escalate', 'case');

        $validated = $request->validate([
            'next_stage' => 'required|string',
            'next_holder_id' => 'required|integer|exists:users,id',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->caseService->escalateCaseToNextStage(
                $caseId,
                $validated['next_stage'],
                $validated['next_holder_id'],
                $validated['reason'] ?? null
            );

            return back()->with('success', 'Case escalated successfully');
        } catch (\Exception $e) {
            return back()->withError('Failed to escalate case: ' . $e->getMessage());
        }
    }
}
