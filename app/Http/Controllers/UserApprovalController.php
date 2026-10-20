<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleAssignment;
use App\Models\RejectionLog;
use App\Models\AuditLog;
use App\Models\Organization;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Province;
use App\Models\District;
use App\Models\FacilityType;
use App\Models\Facility;
use App\Notifications\UserApprovedNotification;
use App\Notifications\UserRejectedNotification;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserApprovalController extends Controller
{
    /**
     * Show all pending users.
     */
    public function pending(Request $request)
    {
        $query = User::where('status', 'pending');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filter by organization
        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->input('organization_id'));
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        $pendingUsers = $query->orderBy('created_at', 'desc')->paginate(15);
        $organizations = Organization::all();
        $departments = Department::all();

        return view('super-admin.pending-users.index', compact('pendingUsers', 'organizations', 'departments'));
    }

    /**
     * Show user profile and role assignment form.
     */
    public function show(User $user)
    {
        $user->load(['organization', 'department', 'designation', 'facility', 'facilityType', 'province', 'district']);
        $roles = Role::where('name', '!=', 'super_admin')->get();
        $approver = auth()->user();
        
        // Get user's current role assignments
        $roleAssignments = RoleAssignment::with('assignedByUser')->where('user_id', $user->id)->get();
        
        // Get rejection history
        $rejectionLogs = RejectionLog::with('rejectedByUser')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('super-admin.users.show', compact('user', 'roles', 'approver', 'roleAssignments', 'rejectionLogs'));
    }

    /**
     * Approve user and assign role.
     */
    public function approve(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $oldData = [
            'status' => $user->status,
            'approved_by' => $user->approved_by,
            'approved_at' => $user->approved_at,
            'roles' => $user->roles->pluck('name')->toArray(),
        ];

        DB::beginTransaction();
        try {
            // Update user status
            $user->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Attach role to user
            $role = Role::find($validated['role_id']);
            $user->roles()->attach($role->id);

            // Log role assignment
            RoleAssignment::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'assigned_by' => auth()->id(),
            ]);

            $newData = [
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'roles' => $user->roles->pluck('name')->toArray(),
            ];

            DB::commit();

            // Log forensic audit
            \App\Services\ForensicAuditService::logUserApproval([
                'resource_type' => 'User',
                'resource_path' => "/user-approval/{$user->id}",
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => "{$user->name} ({$user->email})",
                'old_values' => $oldData,
                'new_values' => $newData,
                'component' => 'UserApprovalController@approve',
                'http_code' => 200,
            ]);

            // Send approval notification email
            $user->notify(new UserApprovedNotification($user, $role));

            // Send SMS approval notification
            try {
                $smsService = new SMSService();
                $firstName = $user->name ? explode(' ', $user->name)[0] : 'User';
                $surname = $user->name ? (isset(explode(' ', $user->name)[1]) ? explode(' ', $user->name)[1] : '') : '';
                $smsService->sendApprovalNotification($user->phone_number, $firstName, $surname);
                Log::info('Account approval SMS sent', ['user_id' => $user->id, 'email' => $user->email]);
            } catch (\Exception $e) {
                Log::error('Failed to send approval SMS', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                // Don't fail the approval if SMS fails
            }

            // Return JSON for AJAX requests, redirect for regular requests
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "User {$user->name} approved and assigned {$role->display_name} role.",
                    'user_name' => $user->name,
                    'role_name' => $role->display_name,
                    'redirect_url' => route('dashboards.super_admin'),
                ], 200);
            }

            return redirect()->route('user-approval.pending')
                ->with('success', "User {$user->name} approved and assigned {$role->display_name} role.");
        } catch (\Exception $e) {
            DB::rollback();

            // Log failure
            \App\Services\ForensicAuditService::log([
                'action' => 'USER_APPROVAL_FAILED',
                'resource_type' => 'User',
                'resource_path' => "/user-approval/{$user->id}",
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => "{$user->name} ({$user->email})",
                'component' => 'UserApprovalController@approve',
                'http_code' => 500,
                'error_message' => $e->getMessage(),
                'classification' => 'SECURITY_SENSITIVE',
            ]);

            // Return JSON for AJAX requests, redirect for regular requests
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while approving the user.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return redirect()->route('user-approval.show', $user)
                ->with('error', 'An error occurred while approving the user.');
        }
    }

    /**
     * Reject user.
     */
    public function reject(Request $request, User $user)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $oldData = [
            'status' => $user->status,
            'rejection_reason' => null,
            'rejected_at' => null,
        ];

        DB::beginTransaction();
        try {
            $user->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now(),
                'can_reapply_at' => now()->addDays(7),
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Log rejection
            RejectionLog::create([
                'user_id' => $user->id,
                'reason' => $validated['rejection_reason'],
                'rejected_by' => auth()->id(),
            ]);

            $newData = [
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now(),
            ];

            DB::commit();

            // Log forensic audit
            \App\Services\ForensicAuditService::logUserRejection([
                'resource_type' => 'User',
                'resource_path' => "/user-approval/{$user->id}",
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => "{$user->name} ({$user->email})",
                'old_values' => $oldData,
                'new_values' => $newData,
                'component' => 'UserApprovalController@reject',
                'http_code' => 200,
            ]);

            // Send rejection notification
            $user->notify(new UserRejectedNotification($user, $validated['rejection_reason']));

            return redirect()->route('user-approval.pending')
                ->with('success', "User {$user->name} rejected.");
        } catch (\Exception $e) {
            DB::rollback();

            // Log failure
            \App\Services\ForensicAuditService::log([
                'action' => 'USER_REJECTION_FAILED',
                'resource_type' => 'User',
                'resource_path' => "/user-approval/{$user->id}",
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => "{$user->name} ({$user->email})",
                'component' => 'UserApprovalController@reject',
                'http_code' => 500,
                'error_message' => $e->getMessage(),
                'classification' => 'SECURITY_SENSITIVE',
            ]);

            return redirect()->route('user-approval.show', $user)
                ->with('error', 'An error occurred while rejecting the user.');
        }
    }

    /**
     * Assign role to pending user (called after viewing).
     */
    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($user->status !== 'pending') {
            return redirect()->route('user-approval.pending')
                ->with('warning', 'User is no longer in pending status.');
        }

        return $this->approve($request, $user);
    }

    /**
     * Show all users (approved, pending, rejected) with live search.
     */
    public function index(Request $request)
    {
        $query = User::with(['organization', 'department', 'designation', 'roles', 'province', 'district', 'facility']);

        // Live search by name, email, or organization
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhereHas('organization', function ($org) use ($search) {
                      $org->where('name', 'like', "%$search%");
                  });
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(50);
        $organizations = Organization::orderBy('name')->get();

        // Get user statistics
        $approvedCount = User::where('status', 'approved')->count();
        $pendingCount = User::where('status', 'pending')->count();
        $rejectedCount = User::where('status', 'rejected')->count();

        return view('super-admin.users.index', compact('users', 'organizations', 'approvedCount', 'pendingCount', 'rejectedCount'));
    }

    /**
     * Show audit logs filtered by type.
     */
    public function auditLogs(Request $request)
    {
        // For backward compatibility, keep role assignments and rejections views
        $roleAssignments = RoleAssignment::with(['user', 'role', 'assignedByUser'])
            ->orderBy('assigned_at', 'desc')
            ->paginate(15, ['*'], 'role_page');

        $rejectionLogs = RejectionLog::with(['user', 'rejectedByUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'rejection_page');

        // All audit logs - will be filtered by dropdown on frontend
        $auditLogs = [];
        try {
            $auditLogs = AuditLog::with('user')
                ->orderBy('timestamp_utc', 'desc')
                ->limit(100)
                ->get();
        } catch (\Exception $e) {
            // Table might not exist yet, silently continue
        }

        return view('super-admin.audit-logs.index', compact(
            'roleAssignments',
            'rejectionLogs',
            'auditLogs'
        ));
    }

    /**
     * Show organization dashboard with approval statistics.
     */
    public function dashboard(Request $request)
    {
        $statistics = [
            'pending_count' => User::where('status', 'pending')->count(),
            'approved_count' => User::where('status', 'approved')->count(),
            'rejected_count' => User::where('status', 'rejected')->count(),
            'total_count' => User::count(),
        ];

        // Get organization-wise statistics if organization_id filter is provided
        $organizationStats = null;
        if ($request->filled('organization_id')) {
            $organizationId = $request->input('organization_id');
            $organizationStats = [
                'pending_count' => User::where('organization_id', $organizationId)
                    ->where('status', 'pending')
                    ->count(),
                'approved_count' => User::where('organization_id', $organizationId)
                    ->where('status', 'approved')
                    ->count(),
                'rejected_count' => User::where('organization_id', $organizationId)
                    ->where('status', 'rejected')
                    ->count(),
                'total_count' => User::where('organization_id', $organizationId)->count(),
            ];
        }

        // Get recent approvals and rejections
        $recentApprovals = User::where('status', 'approved')
            ->whereNotNull('approved_at')
            ->orderBy('approved_at', 'desc')
            ->limit(10)
            ->get();

        $recentRejections = RejectionLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $organizations = Organization::orderBy('name')->get();

        return view('super-admin.dashboard.index', compact(
            'statistics',
            'organizationStats',
            'recentApprovals',
            'recentRejections',
            'organizations'
        ));
    }

    /**
     * Allow rejected user to reapply.
     */
    public function retryApplication(Request $request)
    {
        $user = auth()->user();

        if (!$user->isRejected()) {
            return redirect()->route('dashboard')
                ->with('warning', 'You are not a rejected user.');
        }

        if (!$user->canRetryApplication()) {
            $daysRemaining = $user->getDaysUntilRetry();
            return redirect()->route('pending')
                ->with('error', "You can reapply in {$daysRemaining} day(s).");
        }

        try {
            $user->retryApplication();

            return redirect()->route('pending')
                ->with('success', 'Your application has been reset to pending. The Super Admin team will review it again.');
        } catch (\Exception $e) {
            return redirect()->route('pending')
                ->with('error', 'An error occurred while processing your reapplication.');
        }
    }

    /**
     * Reset user password to hsc2026 and send it via email.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Fixed temporary password
        $temporaryPassword = 'password123';
        
        try {
            DB::beginTransaction();
            
            // Update user password
            $user->update([
                'password' => \Hash::make($temporaryPassword),
            ]);
            
            // Log audit trail
            \App\Services\ForensicAuditService::log([
                'action' => 'USER_PASSWORD_RESET',
                'resource_type' => 'User',
                'resource_path' => "/users/{$user->id}",
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => $user->name . " ({$user->email})",
                'component' => 'UserApprovalController@resetPassword',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'classification' => 'SECURITY_SENSITIVE',
                'data_after' => [
                    'password_reset' => true,
                    'reset_by' => auth()->user()->name,
                    'reset_at' => now()->toIso8601String(),
                ],
            ]);
            
            DB::commit();
            
            // Send email with password
            $user->notify(new \App\Notifications\PasswordResetNotification($temporaryPassword));
            
            Log::info('User password reset successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'reset_by' => auth()->user()->name,
                'timestamp' => now(),
            ]);
            
            // Return JSON if AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset successful.',
                    'user_email' => $user->email,
                ]);
            }
            
            return redirect()->route('user-approval.show', $user)
                ->with('success', "Password reset successful. The password has been set to: password123. Email notification has been sent to {$user->email}.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reset user password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            // Return JSON if AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to reset password. Please try again.',
                ], 400);
            }
            
            return redirect()->route('user-approval.show', $user)
                ->with('error', 'Failed to reset password. Please try again.');
        }
    }

    /**
     * Update user account status (activate, inactivate, suspend)
     */
    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $user->account_status ?? 'unknown';
            $newStatus = $validated['status'];

            // Update user account status
            $user->update([
                'account_status' => $newStatus,
            ]);

            // Log audit trail
            \App\Services\ForensicAuditService::log([
                'action' => 'USER_ACCOUNT_STATUS_CHANGED',
                'resource_type' => 'User',
                'resource_path' => "/users/{$user->id}",
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => $user->name . " ({$user->email})",
                'component' => 'UserApprovalController@updateStatus',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'classification' => 'SECURITY_SENSITIVE',
                'old_values' => ['account_status' => $oldStatus],
                'new_values' => ['account_status' => $newStatus],
                'data_after' => [
                    'account_status' => $newStatus,
                    'updated_by' => auth()->user()->name,
                    'updated_at' => now()->toIso8601String(),
                ],
            ]);

            DB::commit();

            Log::info('User account status updated', [
                'user_id' => $user->id,
                'email' => $user->email,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'updated_by' => auth()->user()->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account status updated successfully.',
                'new_status' => $newStatus,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update user account status', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update account status. Please try again.',
            ], 400);
        }
    }

    /**
     * Get organization options for edit form.
     */
    public function getOrgOptions()
    {
        return response()->json([
            'organizations' => Organization::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'designations' => Designation::orderBy('name')->get(),
            'provinces' => Province::orderBy('name')->get(),
            'districts' => District::with('province')->orderBy('name')->get(['id', 'name', 'province_id']),
            'facility_types' => FacilityType::orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
        ]);
    }

    /**
     * Update user personal information.
     */
    public function updatePersonal(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $oldData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
            ];

            $user->update($validated);

            DB::commit();

            // Log forensic audit
            \App\Services\ForensicAuditService::log([
                'action' => 'USER_PERSONAL_INFO_UPDATED',
                'resource_type' => 'User',
                'resource_path' => "/user-approval/{$user->id}",
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => "{$user->name} ({$user->email})",
                'component' => 'UserApprovalController@updatePersonal',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'classification' => 'SECURITY_SENSITIVE',
                'data_before' => $oldData,
                'data_after' => $validated,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Personal information updated successfully.',
                ]);
            }

            return redirect()->route('user-approval.show', $user)
                ->with('success', 'Personal information updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update personal information.',
                ], 400);
            }

            return redirect()->route('user-approval.show', $user)
                ->with('error', 'Failed to update personal information.');
        }
    }

    /**
     * Update user organization and placement information.
     */
    public function updateOrg(Request $request, User $user)
    {
        $validated = $request->validate([
            'organization_id' => 'nullable|exists:organizations,id',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'facility_type_id' => 'nullable|exists:facility_types,id',
            'facility_id' => 'nullable|exists:facilities,id',
        ]);

        try {
            DB::beginTransaction();

            $oldData = [
                'organization_id' => $user->organization_id,
                'department_id' => $user->department_id,
                'designation_id' => $user->designation_id,
                'province_id' => $user->province_id,
                'district_id' => $user->district_id,
                'facility_type_id' => $user->facility_type_id,
                'facility_id' => $user->facility_id,
            ];

            $user->update($validated);

            DB::commit();

            // Log forensic audit
            \App\Services\ForensicAuditService::log([
                'action' => 'USER_ORG_INFO_UPDATED',
                'resource_type' => 'User',
                'resource_path' => "/user-approval/{$user->id}",
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => "{$user->name} ({$user->email})",
                'component' => 'UserApprovalController@updateOrg',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'classification' => 'SECURITY_SENSITIVE',
                'data_before' => $oldData,
                'data_after' => $validated,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Organization information updated successfully.',
                ]);
            }

            return redirect()->route('user-approval.show', $user)
                ->with('success', 'Organization information updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update organization information.',
                ], 400);
            }

            return redirect()->route('user-approval.show', $user)
                ->with('error', 'Failed to update organization information.');
        }
    }

    /**
     * Get audit detail information via AJAX.
     */
    public function getAuditDetail(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'role') {
            $assignment = RoleAssignment::with(['user', 'role', 'assignedByUser'])->find($id);
            
            if (!$assignment) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            return response()->json([
                'type' => 'role',
                'logType' => 'Role Assignment',
                'id' => $assignment->id,
                'user' => [
                    'name' => $assignment->user->name,
                    'email' => $assignment->user->email,
                ],
                'role' => $assignment->role->display_name ?? $assignment->role->name,
                'assignedBy' => $assignment->assignedByUser->name ?? 'System',
                'assignedAt' => $assignment->assigned_at->format('M d, Y H:i'),
                'status' => $assignment->removed_at ? 'Removed' : 'Active',
                'details' => [
                    'Assigned to' => $assignment->user->name,
                    'Role' => $assignment->role->display_name ?? $assignment->role->name,
                    'Permission Level' => $assignment->role->display_name ? ucfirst(str_replace('_', ' ', $assignment->role->display_name)) : 'N/A',
                    'Assigned By' => $assignment->assignedByUser->name ?? 'System',
                    'Assignment Date' => $assignment->assigned_at->format('M d, Y H:i'),
                ],
                'actionsPerformed' => [
                    'Pages Visited' => 'User Management, Roles, User Profile',
                    'Activities' => 'Viewed role list, Assigned role to user, Saved changes, Updated user permissions',
                    'Total Actions' => 4,
                ],
            ]);
        } elseif ($type === 'rejection') {
            $rejection = RejectionLog::with(['user', 'rejectedByUser'])->find($id);
            
            if (!$rejection) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            return response()->json([
                'type' => 'rejection',
                'logType' => 'User Rejection',
                'id' => $rejection->id,
                'user' => [
                    'name' => $rejection->user->name,
                    'email' => $rejection->user->email,
                ],
                'reason' => $rejection->reason,
                'rejectedBy' => $rejection->rejectedByUser->name ?? 'System',
                'rejectedAt' => $rejection->created_at->format('M d, Y H:i'),
                'details' => [
                    'Rejected User' => $rejection->user->name,
                    'Email' => $rejection->user->email,
                    'Rejection Reason' => $rejection->reason,
                    'Rejected By' => $rejection->rejectedByUser->name ?? 'System',
                    'Rejection Date' => $rejection->created_at->format('M d, Y H:i'),
                ],
                'actionsPerformed' => [
                    'Pages Visited' => 'User Approval, User Details, Rejection Form',
                    'Activities' => 'Reviewed user application, Analyzed user credentials, Added rejection reason, Submitted rejection, Saved logs',
                    'Total Actions' => 5,
                ],
            ]);
        } elseif ($type === 'activity') {
            $log = AuditLog::with('user')->find($id);
            
            if (!$log) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            return response()->json([
                'type' => 'activity',
                'logType' => 'User Activity',
                'id' => $log->id,
                'user' => [
                    'name' => $log->user->name ?? 'System',
                    'email' => $log->user->email ?? 'N/A',
                ],
                'action' => $log->action,
                'status' => $log->status,
                'timestamp' => $log->timestamp_utc->format('M d, Y H:i:s'),
                'details' => [
                    'Action Type' => $log->action,
                    'Component' => $log->component,
                    'Resource' => $log->affected_entity,
                    'Classification' => $log->classification,
                    'Status' => $log->status,
                    'IP Address' => $log->ip_address,
                    'Timestamp' => $log->timestamp_utc->format('M d, Y H:i:s UTC'),
                ],
                'actionsPerformed' => [
                    'Pages Visited' => $log->resource_path,
                    'Activities' => $log->action . ' - ' . $log->component,
                    'Total Actions' => 1,
                ],
            ]);
        } elseif ($type === 'access') {
            $log = AuditLog::with('user')->find($id);
            
            if (!$log) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            return response()->json([
                'type' => 'access',
                'logType' => 'System Access',
                'id' => $log->id,
                'user' => [
                    'name' => $log->user->name ?? 'System',
                    'email' => $log->user->email ?? 'N/A',
                ],
                'action' => $log->action,
                'status' => $log->status,
                'timestamp' => $log->timestamp_utc->format('M d, Y H:i:s'),
                'details' => [
                    'Access Type' => $log->action,
                    'User' => $log->user->name ?? 'Unknown',
                    'IP Address' => $log->ip_address,
                    'IP Version' => $log->ip_version,
                    'User Agent' => substr($log->user_agent, 0, 60) . '...',
                    'Location' => $log->geo_location,
                    'Status' => $log->status,
                    'Timestamp' => $log->timestamp_utc->format('M d, Y H:i:s UTC'),
                ],
                'actionsPerformed' => [
                    'Pages Visited' => $log->resource_path,
                    'Activities' => $log->action . ' from ' . $log->ip_address,
                    'Total Actions' => 1,
                ],
            ]);
        } elseif ($type === 'login') {
            $log = AuditLog::with('user')->find($id);
            
            if (!$log) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            return response()->json([
                'type' => 'login',
                'logType' => 'Failed Login Attempt',
                'id' => $log->id,
                'user' => [
                    'name' => $log->user->name ?? 'Unknown',
                    'email' => $log->user->email ?? 'N/A',
                ],
                'status' => 'FAILED',
                'timestamp' => $log->timestamp_utc->format('M d, Y H:i:s'),
                'details' => [
                    'Attempted User' => $log->user->name ?? 'Unknown',
                    'Email' => $log->user->email ?? 'N/A',
                    'IP Address' => $log->ip_address,
                    'IP Version' => $log->ip_version,
                    'Hostname' => $log->hostname ?? 'Unknown',
                    'Location' => $log->geo_location,
                    'Error' => $log->error_message ?? 'Invalid credentials',
                    'Timestamp' => $log->timestamp_utc->format('M d, Y H:i:s UTC'),
                ],
                'actionsPerformed' => [
                    'Pages Visited' => '/login',
                    'Activities' => 'Failed login attempt - ' . ($log->error_message ?? 'Invalid credentials'),
                    'Total Actions' => 1,
                ],
            ]);
        } elseif ($type === 'auditlog') {
            $log = AuditLog::with('user')->find($id);
            
            if (!$log) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            return response()->json([
                'type' => 'auditlog',
                'logType' => $log->action,
                'id' => $log->id,
                'user' => [
                    'name' => $log->user->name ?? 'System',
                    'email' => $log->user->email ?? 'N/A',
                ],
                'action' => $log->action,
                'status' => $log->status,
                'timestamp' => $log->timestamp_utc->format('M d, Y H:i:s'),
                'details' => [
                    'Event ID' => $log->event_id,
                    'Action' => $log->action,
                    'Component' => $log->component,
                    'Resource' => $log->affected_entity ?? $log->resource_path,
                    'Status' => $log->status,
                    'IP Address' => $log->ip_address,
                    'IP Version' => $log->ip_version,
                    'User Agent' => substr($log->user_agent ?? '', 0, 60) . (strlen($log->user_agent ?? '') > 60 ? '...' : ''),
                    'Classification' => $log->classification,
                    'Timestamp' => $log->timestamp_utc->format('M d, Y H:i:s UTC'),
                    'Error' => $log->error_message ?? 'None',
                ],
                'actionsPerformed' => [
                    'Pages Visited' => $log->resource_path,
                    'Activities' => $log->action . ' - ' . $log->component,
                    'Total Actions' => 1,
                ],
            ]);
        }

        return response()->json(['error' => 'Invalid type'], 400);
    }
}
