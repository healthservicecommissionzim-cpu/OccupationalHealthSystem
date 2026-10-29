<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Services\Roster\RosterService;
use App\Models\StaffRoster;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RosterController extends Controller
{
    protected $rosterService;

    protected $rosterAccessService;
    
    protected $importService;

    protected $assignmentService;

    public function __construct(
        RosterService $rosterService, 
        \App\Services\Roster\RosterAccessService $rosterAccessService,
        \App\Services\Roster\ImportService $importService,
        \App\Services\Roster\AssignmentService $assignmentService
    )
    {
        // Auth middleware for all methods
        $this->middleware('auth');
        
        // Policy-based authorization
        $this->authorizeResource(StaffRoster::class, 'roster');
        
        $this->rosterService = $rosterService;
        $this->rosterAccessService = $rosterAccessService;
        $this->importService = $importService;
        $this->assignmentService = $assignmentService;
    }

    /**
     * Display a listing of staff rosters with pagination and filtering
     */
    public function index(Request $request)
    {
        // Authorization is handled by authorizeResource in constructor
        
        $query = StaffRoster::with(['user', 'primaryFacility', 'createdBy']);

        // Apply filters
        if ($request->filled('staff_type')) {
            $query->byStaffType($request->staff_type);
        }

        if ($request->filled('facility_id')) {
            $query->byFacility($request->facility_id);
        }

        if ($request->filled('active')) {
            if ($request->active === '1') {
                $query->active();
            } else {
                $query->where('active', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $rosters = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get data for filters
        $facilities = Facility::orderBy('name')->get();
        $staffTypes = ['permanent', 'locum', 'student', 'rotational'];

        // Check for multiple assignment conflicts
        $conflicts = $this->assignmentService->checkMultipleAssignments();

        return view('system.roster.manage-rosters', compact('rosters', 'facilities', 'staffTypes', 'conflicts'));
    }

    /**
     * Show the form for creating a new roster
     */
    public function create()
    {
        // Authorization is handled by authorizeResource in constructor
        
        $users = User::where('approval_status', 'approved')
            ->orderBy('first_name')
            ->orderBy('surname')
            ->get();
        $facilities = Facility::orderBy('name')->get();
        $staffTypes = ['permanent', 'locum', 'student', 'rotational'];

        return view('system.roster.form', compact('users', 'facilities', 'staffTypes'));
    }

    /**
     * Store a newly created roster in storage
     */
    public function store(Request $request)
    {
        // Authorization is handled by authorizeResource in constructor
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'staff_type' => 'required|in:permanent,locum,student,rotational',
            'primary_facility_id' => 'required|exists:facilities,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string',
        ]);

        try {
            $roster = $this->rosterService->createRoster(
                $request->user_id,
                $request->staff_type,
                $request->primary_facility_id,
                \Carbon\Carbon::parse($request->start_date),
                $request->end_date ? \Carbon\Carbon::parse($request->end_date) : null,
                Auth::id()
            );

            return redirect()->route('system.roster.index')
                ->with('success', 'Staff roster created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to create roster', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'Failed to create roster: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified roster
     */
    public function edit(StaffRoster $roster)
    {
        // Authorization is handled by authorizeResource in constructor
        
        $roster->load(['user', 'primaryFacility']);
        
        $users = User::where('approval_status', 'approved')
            ->orderBy('first_name')
            ->orderBy('surname')
            ->get();
        $facilities = Facility::orderBy('name')->get();
        $staffTypes = ['permanent', 'locum', 'student', 'rotational'];

        return view('system.roster.form', compact('roster', 'users', 'facilities', 'staffTypes'));
    }

    /**
     * Update the specified roster in storage
     */
    public function update(Request $request, StaffRoster $roster)
    {
        // Authorization is handled by authorizeResource in constructor
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'staff_type' => 'required|in:permanent,locum,student,rotational',
            'primary_facility_id' => 'required|exists:facilities,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        try {
            $attributes = [
                'user_id' => $request->user_id,
                'staff_type' => $request->staff_type,
                'primary_facility_id' => $request->primary_facility_id,
                'start_date' => \Carbon\Carbon::parse($request->start_date),
                'end_date' => $request->end_date ? \Carbon\Carbon::parse($request->end_date) : null,
                'notes' => $request->notes,
            ];

            if ($request->has('active')) {
                $attributes['active'] = $request->boolean('active');
            }

            $roster = $this->rosterService->updateRoster(
                $roster->id,
                $attributes,
                Auth::id()
            );

            return redirect()->route('system.roster.index')
                ->with('success', 'Staff roster updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to update roster', [
                'roster_id' => $roster->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'Failed to update roster: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Soft delete (deactivate) the specified roster
     */
    public function destroy(StaffRoster $roster)
    {
        // Authorization is handled by authorizeResource in constructor
        
        try {
            $this->rosterService->deactivateRoster($roster->id, Auth::id());

            return redirect()->route('system.roster.index')
                ->with('success', 'Staff roster deactivated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to deactivate roster', [
                'roster_id' => $roster->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'Failed to deactivate roster: ' . $e->getMessage());
        }
    }

    /**
     * Display read-only roster view for HR roles
     * Filters rosters based on user role and permissions
     */
    public function viewRosters(Request $request)
    {
        $user = Auth::user();
        
        // Check if user can view any rosters
        $this->authorize('viewAny', StaffRoster::class);

        // Get rosters filtered by user role
        $query = $this->rosterAccessService->getRostersForUser($user);

        // Apply additional filters
        if ($request->filled('staff_type')) {
            $query->where('staff_type', $request->staff_type);
        }

        if ($request->filled('facility_id')) {
            $query->where('primary_facility_id', $request->facility_id);
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active === '1');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $rosters = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get facilities for filter dropdown (based on user access)
        $facilities = $this->getFacilitiesForUser($user);
        
        $staffTypes = ['permanent', 'locum', 'student', 'rotational'];

        return view('roster.view', compact('rosters', 'facilities', 'staffTypes', 'user'));
    }

    /**
     * Get facilities for filter dropdown based on user role
     */
    private function getFacilitiesForUser(User $user)
    {
        if ($user->hasAnyRole(['super_admin', 'central_hr', 'mohhc_hr', 'hsc_conditions'])) {
            return Facility::orderBy('name')->get();
        }

        if ($user->hasRole('district_hr') && $user->district_id) {
            return Facility::where('district_id', $user->district_id)
                ->orderBy('name')
                ->get();
        }

        if ($user->hasRole('hospital_hr') && $user->facility_id) {
            return Facility::where('id', $user->facility_id)
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    /**
     * Show the bulk import form
     */
    public function importForm()
    {
        // Only Super Admin can import
        $this->authorize('create', StaffRoster::class);
        
        return view('system.roster.import');
    }

    /**
     * Process CSV file upload and import rosters
     */
    public function processImport(Request $request)
    {
        // Only Super Admin can import
        $this->authorize('create', StaffRoster::class);
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
        ]);

        try {
            // Store uploaded file temporarily
            $file = $request->file('csv_file');
            $filePath = $file->getRealPath();

            // Process import
            $result = $this->importService->importRostersCsv($filePath, Auth::id());

            if ($result['success']) {
                return back()->with('success', "Successfully imported {$result['imported']} roster records.");
            } else {
                // Return validation errors
                return back()
                    ->with('error', 'Import failed due to validation errors.')
                    ->with('import_errors', $result['errors'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Failed to process roster import', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'Failed to process import: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template with example data
     */
    public function downloadTemplate()
    {
        // Only Super Admin can download template
        $this->authorize('create', StaffRoster::class);

        // Create CSV content with headers and example rows
        $headers = ['user_email', 'staff_type', 'primary_facility_name', 'start_date', 'end_date'];
        
        $exampleRows = [
            ['john.doe@example.com', 'rotational', 'Harare Central Hospital', '2024-01-01', '2024-12-31'],
            ['jane.smith@example.com', 'locum', 'Parirenyatwa Hospital', '2024-06-01', ''],
            ['student@med.edu', 'student', 'Sally Mugabe Hospital', '2024-09-01', '2025-05-31'],
            ['permanent.staff@health.gov.zw', 'permanent', 'Chitungwiza Hospital', '2023-01-01', ''],
        ];

        // Build CSV content
        $csvContent = implode(',', $headers) . "\n";
        foreach ($exampleRows as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        // Set headers for file download
        $fileName = 'roster_import_template_' . date('Y-m-d') . '.csv';
        
        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
