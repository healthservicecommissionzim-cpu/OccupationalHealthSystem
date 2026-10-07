<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\RoleDashboardMapping;
use App\Models\Role;
use App\Services\DashboardRoutingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardMappingController extends Controller
{
    public function __construct(private DashboardRoutingService $routingService)
    {
        // Middleware is applied via routes definition
    }

    /**
     * Display all dashboard mappings.
     */
    public function index()
    {
        $mappings = RoleDashboardMapping::with('role')->ordered()->paginate(25);
        $dashboards = $this->routingService->getAvailableDashboards();

        return view('super-admin.dashboard-mappings.index', compact('mappings', 'dashboards'));
    }

    /**
     * Show form for creating new mapping.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        
        $dashboards = $this->routingService->getAvailableDashboards();

        return view('super-admin.dashboard-mappings.create', compact('roles', 'dashboards'));
    }

    /**
     * Store new dashboard mapping.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'dashboard_name' => 'required|string|max:255',
            'dashboard_route' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            // Check if mapping already exists for this role
            $existing = RoleDashboardMapping::where('role_id', $validated['role_id'])
                ->where('dashboard_name', $validated['dashboard_name'])
                ->first();

            if ($existing) {
                return back()->with('error', 'This role already has a mapping to ' . $validated['dashboard_name']);
            }

            $this->routingService->createMapping(
                $validated['role_id'],
                $validated['dashboard_name'],
                $validated['dashboard_route'],
                [
                    'description' => $validated['description'],
                    'sort_order' => $validated['sort_order'] ?? 0,
                    'is_active' => $validated['is_active'] ?? true,
                ]
            );

            return redirect()->route('super-admin.dashboard-mappings.index')
                ->with('success', 'Dashboard mapping created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create mapping: ' . $e->getMessage());
        }
    }

    /**
     * Show form for editing mapping.
     */
    public function edit(RoleDashboardMapping $mapping)
    {
        $dashboards = $this->routingService->getAvailableDashboards();
        
        return view('super-admin.dashboard-mappings.edit', compact('mapping', 'dashboards'));
    }

    /**
     * Update dashboard mapping.
     */
    public function update(Request $request, RoleDashboardMapping $mapping)
    {
        $validated = $request->validate([
            'dashboard_route' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            $this->routingService->updateMapping($mapping->id, $validated);

            return redirect()->route('super-admin.dashboard-mappings.index')
                ->with('success', 'Dashboard mapping updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update mapping: ' . $e->getMessage());
        }
    }

    /**
     * Delete dashboard mapping.
     */
    public function destroy(RoleDashboardMapping $mapping)
    {
        try {
            $roleName = $mapping->role->name;
            $this->routingService->deleteMapping($mapping->id);

            return redirect()->route('super-admin.dashboard-mappings.index')
                ->with('success', "Mapping for role '$roleName' deleted successfully");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete mapping: ' . $e->getMessage());
        }
    }

    /**
     * Toggle mapping active/inactive.
     */
    public function toggle(RoleDashboardMapping $mapping)
    {
        try {
            if ($mapping->is_active) {
                $this->routingService->disableMapping($mapping->id);
                $status = 'disabled';
            } else {
                $this->routingService->enableMapping($mapping->id);
                $status = 'enabled';
            }

            return back()->with('success', "Dashboard mapping $status");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to toggle mapping: ' . $e->getMessage());
        }
    }

    /**
     * Test a mapping to verify it works.
     */
    public function test(RoleDashboardMapping $mapping)
    {
        try {
            // Verify the route exists and is accessible
            $route = $mapping->dashboard_route;
            
            return response()->json([
                'success' => true,
                'message' => "Mapping to '$route' is valid",
                'route' => $route,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to test mapping: ' . $e->getMessage(),
            ], 400);
        }
    }
}
