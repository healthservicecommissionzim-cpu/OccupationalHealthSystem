<?php

namespace App\Http\Controllers;

use App\Rules\StrongPassword;
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
     * Get profile data as JSON (for modal)
     */
    public function getProfileData()
    {
        $user = Auth::user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'status' => $user->status,
                'created_at' => $user->created_at->format('F d, Y'),
                'updated_at' => $user->updated_at->format('F d, Y \a\t h:i A'),
                'initials' => strtoupper(substr(implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', trim($user->name)))), 0, 2)),
            ],
            'roles' => $user->roles->map(fn($role) => [
                'id' => $role->id,
                'display_name' => $role->display_name,
            ]),
            'organization' => $user->organization ? [
                'id' => $user->organization->id,
                'name' => $user->organization->name,
            ] : null,
            'facility' => $user->facility ? [
                'id' => $user->facility->id,
                'name' => $user->facility->name,
            ] : null,
            'department' => $user->department ? [
                'id' => $user->department->id,
                'name' => $user->department->name,
            ] : null,
            'designation' => $user->designation ? [
                'id' => $user->designation->id,
                'name' => $user->designation->name,
            ] : null,
            'province' => $user->province ? [
                'id' => $user->province->id,
                'name' => $user->province->name,
            ] : null,
            'district' => $user->district ? [
                'id' => $user->district->id,
                'name' => $user->district->name,
            ] : null,
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
            'password' => ['required', 'confirmed', new StrongPassword],
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
