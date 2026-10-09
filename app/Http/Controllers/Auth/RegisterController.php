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
        $districts = District::all();
        $facilityTypes = FacilityType::all();
        $facilities = Facility::all();
        return view('auth.register', compact('organizations', 'departments', 'designations', 'provinces', 'districts', 'facilityTypes', 'facilities'));
    }

    public function store(Request $request)
    {
        $organizationId = $request->input('organization_id');
        $organization = Organization::find($organizationId);
        $designationId = $request->input('designation_id');
        $designation = Designation::find($designationId);

        // Build validation rules based on organization
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone_number' => 'required|string|max:20',
            'organization_id' => 'required|exists:organizations,id',
            'designation_id' => 'required|exists:designations,id',
            'password' => ['required', 'string', 'confirmed', new StrongPassword],
        ];

        // Check designation type
        $isHRProvincial = $designation && strpos($designation->name, 'Human Resources (Province)') !== false;
        $isHRDistrict = $designation && strpos($designation->name, 'Human Resources (District)') !== false;
        $isHRHospital = $designation && strpos($designation->name, 'Human Resources (Hospital)') !== false;
        $isHROther = $designation && (
            strpos($designation->name, 'Human Resources Officer') !== false ||
            strpos($designation->name, 'Human Resources Manager') !== false
        );
        $isHR = $isHRProvincial || $isHRDistrict || $isHRHospital || $isHROther;
        $isDMO = $designation && strpos($designation->name, 'District Medical Officer') !== false;
        $isPMD = $designation && strpos($designation->name, 'Provincial Medical Director') !== false;
        $isCMO = $designation && strpos($designation->name, 'Chief Medical Officer') !== false;

        // Check if facility type is Central Hospital
        $facilityTypeId = $request->input('facility_type_id');
        $isCentralHospital = false;
        if ($facilityTypeId) {
            $facilityType = \App\Models\FacilityType::find($facilityTypeId);
            $isCentralHospital = $facilityType && strtolower($facilityType->name) === 'central hospital';
        }

        // Only require location fields for MoHCC
        if ($organization && $organization->requires_location) {
            if ($isHRProvincial) {
                // Provincial HR: Only Province required
                $rules = array_merge($rules, [
                    'department_id' => 'nullable|exists:departments,id',
                    'province_id' => 'required|exists:provinces,id',
                    'district_id' => 'nullable|exists:districts,id',
                    'facility_type_id' => 'nullable|exists:facility_types,id',
                    'facility_id' => 'nullable|exists:facilities,id',
                ]);
            } elseif ($isHRDistrict) {
                // District HR: Province and District required
                $rules = array_merge($rules, [
                    'department_id' => 'nullable|exists:departments,id',
                    'province_id' => 'required|exists:provinces,id',
                    'district_id' => 'required|exists:districts,id',
                    'facility_type_id' => 'nullable|exists:facility_types,id',
                    'facility_id' => 'nullable|exists:facilities,id',
                ]);
            } elseif ($isHRHospital) {
                // Hospital HR: Province, District, Facility Type, and Facility required
                // UNLESS facility type is Central Hospital - then district is NOT required
                $districtRule = $isCentralHospital ? 'nullable' : 'nullable';
                $rules = array_merge($rules, [
                    'department_id' => 'nullable|exists:departments,id',
                    'province_id' => 'required|exists:provinces,id',
                    'district_id' => $districtRule . '|exists:districts,id',
                    'facility_type_id' => 'required|exists:facility_types,id',
                    'facility_id' => 'required|exists:facilities,id',
                ]);
            } elseif ($isHROther) {
                // Other HR: all location fields are optional (handled by frontend)
                $rules = array_merge($rules, [
                    'department_id' => 'nullable|exists:departments,id',
                    'province_id' => 'nullable|exists:provinces,id',
                    'district_id' => 'nullable|exists:districts,id',
                    'facility_type_id' => 'nullable|exists:facility_types,id',
                    'facility_id' => 'nullable|exists:facilities,id',
                ]);
            } elseif ($isDMO) {
                // DMO: only province and district required
                $rules = array_merge($rules, [
                    'department_id' => 'nullable|exists:departments,id',
                    'province_id' => 'required|exists:provinces,id',
                    'district_id' => 'required|exists:districts,id',
                    'facility_type_id' => 'nullable|exists:facility_types,id',
                    'facility_id' => 'nullable|exists:facilities,id',
                ]);
            } elseif ($isPMD) {
                // PMD: only province required
                $rules = array_merge($rules, [
                    'department_id' => 'nullable|exists:departments,id',
                    'province_id' => 'required|exists:provinces,id',
                    'district_id' => 'nullable|exists:districts,id',
                    'facility_type_id' => 'nullable|exists:facility_types,id',
                    'facility_id' => 'nullable|exists:facilities,id',
                ]);
            } elseif ($isCMO) {
                // CMO: province, facility type, and facility required (district NOT required)
                $rules = array_merge($rules, [
                    'department_id' => 'nullable|exists:departments,id',
                    'province_id' => 'required|exists:provinces,id',
                    'district_id' => 'nullable|exists:districts,id',
                    'facility_type_id' => 'required|exists:facility_types,id',
                    'facility_id' => 'required|exists:facilities,id',
                ]);
            } else {
                // Everyone else: ALL location fields are required (Department, Province, District, Facility Type, Facility)
                // UNLESS facility type is Central Hospital - then district is NOT required
                $districtRule = $isCentralHospital ? 'nullable' : 'required';
                $rules = array_merge($rules, [
                    'department_id' => 'required|exists:departments,id',
                    'province_id' => 'required|exists:provinces,id',
                    'district_id' => $districtRule . '|exists:districts,id',
                    'facility_type_id' => 'required|exists:facility_types,id',
                    'facility_id' => 'required|exists:facilities,id',
                ]);
            }
        }

        $validated = $request->validate($rules);

        // Prepare user data
        $userData = [
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'organization_id' => $validated['organization_id'],
            'designation_id' => $validated['designation_id'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
        ];

        // Add location fields only if provided
        if ($organization && $organization->requires_location) {
            $userData['department_id'] = $validated['department_id'] ?? null;
            $userData['province_id'] = $validated['province_id'] ?? null;
            $userData['district_id'] = $validated['district_id'] ?? null;
            $userData['facility_type_id'] = $validated['facility_type_id'] ?? null;
            $userData['facility_id'] = $validated['facility_id'] ?? null;
        }

        // Create user
        $user = User::create($userData);

        // Log successful registration
        Log::info('User registration successful', ['user_id' => $user->id, 'email' => $user->email]);

        return back()->with('success', 'Account Awaiting Verification - Contact Admin');
    }
}
