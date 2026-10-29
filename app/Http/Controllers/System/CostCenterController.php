<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CostCenterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of cost centers
     */
    public function index(Request $request)
    {
        // Check authorization - cost centers are part of roster management
        $this->authorize('viewAny', \App\Models\StaffRoster::class);
        
        $query = CostCenter::with('facility');

        // Apply filters
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        if ($request->filled('is_active')) {
            if ($request->is_active === '1') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('cost_center_code', 'like', "%{$search}%")
                  ->orWhere('cost_center_name', 'like', "%{$search}%");
            });
        }

        $costCenters = $query->orderBy('facility_id')->orderBy('cost_center_code')->paginate(20);

        // Get data for filters
        $facilities = Facility::orderBy('name')->get();

        return view('system.roster.cost-centers', compact('costCenters', 'facilities'));
    }

    /**
     * Store a newly created cost center in storage
     */
    public function store(Request $request)
    {
        // Check authorization - only Super Admin can create cost centers
        $this->authorize('create', \App\Models\StaffRoster::class);
        
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'cost_center_code' => 'required|string|max:50|unique:cost_centers,cost_center_code',
            'cost_center_name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        try {
            $costCenter = CostCenter::create([
                'facility_id' => $request->facility_id,
                'cost_center_code' => $request->cost_center_code,
                'cost_center_name' => $request->cost_center_name,
                'is_active' => $request->boolean('is_active', true),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cost center created successfully.',
                    'cost_center' => $costCenter->load('facility')
                ], 201);
            }

            return redirect()->route('system.roster.cost-centers.index')
                ->with('success', 'Cost center created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to create cost center', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create cost center.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to create cost center: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified cost center in storage
     */
    public function update(Request $request, CostCenter $costCenter)
    {
        // Check authorization - only Super Admin can update cost centers
        $this->authorize('update', [\App\Models\StaffRoster::class, $costCenter]);
        
        $request->validate([
            'facility_id' => 'sometimes|required|exists:facilities,id',
            'cost_center_code' => 'sometimes|required|string|max:50|unique:cost_centers,cost_center_code,' . $costCenter->id,
            'cost_center_name' => 'sometimes|required|string|max:255',
            'is_active' => 'boolean',
        ]);

        try {
            $costCenter->update($request->only([
                'facility_id',
                'cost_center_code',
                'cost_center_name',
                'is_active'
            ]));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cost center updated successfully.',
                    'cost_center' => $costCenter->load('facility')
                ]);
            }

            return redirect()->route('system.roster.cost-centers.index')
                ->with('success', 'Cost center updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to update cost center', [
                'cost_center_id' => $costCenter->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update cost center.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to update cost center: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified cost center from storage
     */
    public function destroy(CostCenter $costCenter)
    {
        // Check authorization - only Super Admin can delete cost centers
        $this->authorize('delete', [\App\Models\StaffRoster::class, $costCenter]);
        
        try {
            // Check if cost center is linked to any assignments
            if ($costCenter->assignments()->exists()) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete cost center because it is linked to roster assignments.',
                    ], 409);
                }
                return back()->with('error', 'Cannot delete cost center because it is linked to roster assignments.');
            }

            $costCenter->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cost center deleted successfully.'
                ]);
            }

            return redirect()->route('system.roster.cost-centers.index')
                ->with('success', 'Cost center deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete cost center', [
                'cost_center_id' => $costCenter->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete cost center.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete cost center: ' . $e->getMessage());
        }
    }
}
