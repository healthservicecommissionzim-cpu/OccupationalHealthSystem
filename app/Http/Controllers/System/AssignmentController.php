<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Services\Roster\AssignmentService;
use App\Models\RosterAssignment;
use App\Models\StaffRoster;
use App\Models\Facility;
use App\Models\User;
use App\Exceptions\OverlapException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->middleware('auth');
        $this->assignmentService = $assignmentService;
    }

    /**
     * Display a listing of assignments by facility with date ranges
     */
    public function index(Request $request)
    {
        // Check authorization
        $this->authorize('viewAny', \App\Models\StaffRoster::class);
        
        $query = RosterAssignment::with([
            'staffRoster.user',
            'facility',
            'costCenterFacility',
            'supervisor',
            'createdBy'
        ]);

        // Apply filters
        if ($request->filled('facility_id')) {
            $query->atFacility($request->facility_id);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        } else {
            // Default to active assignments
            $query->active();
        }

        if ($request->filled('start_date')) {
            $query->where('assignment_start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('assignment_end_date', '<=', $request->end_date);
        }

        if ($request->filled('assignment_type')) {
            $query->where('assignment_type', $request->assignment_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('staffRoster.user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $assignments = $query->orderBy('assignment_start_date', 'desc')->paginate(20);

        // Get data for filters
        $facilities = Facility::orderBy('name')->get();
        $assignmentTypes = ['rotation', 'locum_contract', 'student_placement'];
        $statuses = ['active', 'completed', 'cancelled'];

        return view('system.roster.facility-assignments', compact(
            'assignments',
            'facilities',
            'assignmentTypes',
            'statuses'
        ));
    }

    /**
     * Store a newly created assignment in storage
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('createAssignment', \App\Models\StaffRoster::class);
        
        $request->validate([
            'staff_roster_id' => 'required|exists:staff_rosters,id',
            'facility_id' => 'required|exists:facilities,id',
            'assignment_start_date' => 'required|date',
            'assignment_end_date' => 'required|date|after:assignment_start_date',
            'cost_center_facility_id' => 'required|exists:facilities,id',
            'assignment_type' => 'required|in:rotation,locum_contract,student_placement',
            'supervisor_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        try {
            $assignment = $this->assignmentService->createAssignment(
                $request->staff_roster_id,
                $request->facility_id,
                \Carbon\Carbon::parse($request->assignment_start_date),
                \Carbon\Carbon::parse($request->assignment_end_date),
                $request->cost_center_facility_id,
                $request->assignment_type,
                Auth::id(),
                $request->supervisor_id,
                $request->notes
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Assignment created successfully.',
                    'assignment' => $assignment->load(['staffRoster.user', 'facility', 'costCenterFacility'])
                ], 201);
            }

            return redirect()->route('system.roster.assignments.index')
                ->with('success', 'Assignment created successfully.');
        } catch (OverlapException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment dates overlap with an existing assignment.',
                    'error' => $e->getMessage()
                ], 422);
            }
            return back()->withErrors(['overlap' => $e->getMessage()])->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to create assignment', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create assignment.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to create assignment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified assignment in storage
     */
    public function update(Request $request, RosterAssignment $assignment)
    {
        // Check authorization
        $this->authorize('updateAssignment', [\App\Models\StaffRoster::class, $assignment]);
        
        $request->validate([
            'assignment_start_date' => 'sometimes|required|date',
            'assignment_end_date' => 'sometimes|required|date|after:assignment_start_date',
            'cost_center_facility_id' => 'sometimes|required|exists:facilities,id',
            'assignment_type' => 'sometimes|required|in:rotation,locum_contract,student_placement',
            'supervisor_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'sometimes|required|in:active,completed,cancelled',
        ]);

        try {
            $attributes = [];

            if ($request->has('assignment_start_date')) {
                $attributes['assignment_start_date'] = \Carbon\Carbon::parse($request->assignment_start_date);
            }

            if ($request->has('assignment_end_date')) {
                $attributes['assignment_end_date'] = \Carbon\Carbon::parse($request->assignment_end_date);
            }

            if ($request->has('cost_center_facility_id')) {
                $attributes['cost_center_facility_id'] = $request->cost_center_facility_id;
            }

            if ($request->has('assignment_type')) {
                $attributes['assignment_type'] = $request->assignment_type;
            }

            if ($request->has('supervisor_id')) {
                $attributes['supervisor_id'] = $request->supervisor_id;
            }

            if ($request->has('notes')) {
                $attributes['notes'] = $request->notes;
            }

            if ($request->has('status')) {
                $attributes['status'] = $request->status;
            }

            $assignment = $this->assignmentService->updateAssignment(
                $assignment->id,
                $attributes,
                Auth::id()
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Assignment updated successfully.',
                    'assignment' => $assignment->load(['staffRoster.user', 'facility', 'costCenterFacility'])
                ]);
            }

            return redirect()->route('system.roster.assignments.index')
                ->with('success', 'Assignment updated successfully.');
        } catch (OverlapException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment dates overlap with an existing assignment.',
                    'error' => $e->getMessage()
                ], 422);
            }
            return back()->withErrors(['overlap' => $e->getMessage()])->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to update assignment', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update assignment.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to update assignment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Cancel the specified assignment
     */
    public function cancel(RosterAssignment $assignment)
    {
        // Check authorization
        $this->authorize('deleteAssignment', [\App\Models\StaffRoster::class, $assignment]);
        
        try {
            $this->assignmentService->cancelAssignment($assignment->id, Auth::id());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Assignment cancelled successfully.'
                ]);
            }

            return redirect()->route('system.roster.assignments.index')
                ->with('success', 'Assignment cancelled successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to cancel assignment', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to cancel assignment.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to cancel assignment: ' . $e->getMessage());
        }
    }
}
