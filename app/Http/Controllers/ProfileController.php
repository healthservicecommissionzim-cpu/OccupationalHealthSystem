<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function show()
    {
        $user = Auth::user();
        $backRoute = $this->getBackRoute($user);
        
        return view('profile.show', [
            'user' => $user,
            'roles' => $user->roles,
            'organization' => $user->organization,
            'facility' => $user->facility,
            'department' => $user->department,
            'designation' => $user->designation,
            'backRoute' => $backRoute,
        ]);
    }

    /**
     * Get the appropriate back route based on user's role
     */
    private function getBackRoute($user)
    {
        $role = $user->roles->first();
        
        if (!$role) {
            return route('dashboard');
        }

        $roleRoutes = [
            'supervisor' => 'dashboards.supervisor',
            'super_admin' => 'dashboards.super_admin',
            'dmo' => 'dashboards.dmo',
            'medical_superintendent' => 'dashboards.clinical_executive',
            'cmo' => 'dashboards.clinical_executive',
            'pmd' => 'dashboards.pmd',
            'hospital_hr' => 'dashboards.hospital_hr',
            'district_hr' => 'dashboards.district_hr',
            'central_hr' => 'dashboards.central_hr',
            'mohhc_hr' => 'dashboards.mohhc_hr',
            'labour_social' => 'dashboards.labour_social',
            'medical_board' => 'dashboards.medical_board',
            'hsc_conditions' => 'dashboards.hsc_conditions',
        ];

        $routeName = $roleRoutes[$role->slug] ?? 'dashboard';
        
        return route($routeName);
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        \Log::info('Profile update attempt', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'wants_json' => $request->wantsJson(),
        ]);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            \Log::warning('Profile update validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::user();
            $user->update($request->only(['name', 'email', 'phone_number']));

            \Log::info('Profile updated successfully', [
                'user_id' => $user->id,
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
            }

            return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to update profile: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show change password form
     */
    public function showChangePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            Auth::user()->update([
                'password' => bcrypt($request->password),
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
            }

            return redirect()->route('profile.show')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Password update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update password: ' . $e->getMessage()], 500);
        }
    }
}
