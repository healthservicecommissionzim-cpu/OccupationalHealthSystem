<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Province;
use App\Models\District;
use App\Models\FacilityType;
use App\Models\Facility;
use App\Models\Role;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function create()
    {
        $organizations = Organization::all();
        $departments = Department::all();
        $designations = Designation::all();
        $provinces = Province::all();
        // Load districts with their province relationship
        $districts = District::with('province')->get();
        $facilityTypes = FacilityType::all();
        $facilities = Facility::all();
        return view('auth.register', compact('organizations', 'departments', 'designations', 'provinces', 'districts', 'facilityTypes', 'facilities'));
    }

    /**
     * Get facility types and facilities for registration form
     */
    public function getFacilityData(Request $request)
    {
        // Get all facilities with their facility types
        $facilities = Facility::with('facilityType')->get();
        
        // Extract unique facility types that have facilities
        $facilityTypeIds = $facilities->pluck('facility_type_id')->unique();
        $facilityTypes = FacilityType::whereIn('id', $facilityTypeIds)->get();

        return response()->json([
            'facilityTypes' => $facilityTypes,
            'facilities' => $facilities->map(function($facility) {
                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'facility_type_id' => $facility->facility_type_id,
                    'province_id' => $facility->province_id,
                    'district_id' => $facility->district_id,
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        // Build validation rules - all location fields are optional
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone_number' => 'required|string|max:20',
            'organization_id' => 'nullable|exists:organizations,id',
            'designation_id' => 'required|exists:designations,id',
            'department_id' => 'nullable|exists:departments,id',
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'facility_type_id' => 'nullable|exists:facility_types,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'password' => ['required', 'string', 'confirmed', new StrongPassword],
        ];

        $validated = $request->validate($rules);

        // Prepare user data
        $userData = [
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'organization_id' => $validated['organization_id'] ?? null,
            'designation_id' => $validated['designation_id'],
            'department_id' => $validated['department_id'] ?? null,
            'province_id' => $validated['province_id'] ?? null,
            'district_id' => $validated['district_id'] ?? null,
            'facility_type_id' => $validated['facility_type_id'] ?? null,
            'facility_id' => $validated['facility_id'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
        ];

        // Create user
        $user = User::create($userData);

        // Log successful registration
        Log::info('User registration successful', ['user_id' => $user->id, 'email' => $user->email]);

        return back()->with('success', 'Account Awaiting Verification - Contact Admin');
    }
}
