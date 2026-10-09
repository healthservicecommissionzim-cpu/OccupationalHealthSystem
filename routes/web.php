<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\FirstAidController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\UserApprovalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReturnToWorkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group.
|
*/

// ─── Public Welcome Page ───────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/resources', function () {
    return view('resources');
})->name('resources');

Route::get('/safety-protocols', function () {
    return view('safety-protocols');
})->name('safety_protocols');

// ─── Public Incident Report Routes ──────────────────────────────────────
Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
Route::get('/incidents/track', [IncidentController::class, 'track'])->name('incidents.track');
Route::post('/incidents/search', [IncidentController::class, 'search'])->name('incidents.search');
Route::post('/incidents/upload-documents', [IncidentController::class, 'publicUploadDocuments'])->name('incidents.public_upload_documents');
Route::get('/incidents/{referenceNumber}/download-pdf', [IncidentController::class, 'downloadPdf'])->name('incidents.downloadPdf');
Route::get('/incidents/witness/{token}', [IncidentController::class, 'witnessStatement'])->name('incidents.witness_statement');
Route::post('/incidents/witness/{token}/submit', [IncidentController::class, 'witnessSubmit'])->name('incidents.witness_submit');

// ─── Public First Aid Routes ────────────────────────────────────────────
Route::get('/first-aid/token', [FirstAidController::class, 'tokenForm'])->name('first_aid.token_form');
Route::post('/first-aid/verify-token', [FirstAidController::class, 'verifyToken'])->name('first_aid.verify_token');
Route::get('/first-aid/create', [FirstAidController::class, 'create'])->name('first_aid.create');
Route::post('/first-aid', [FirstAidController::class, 'store'])->name('first_aid.store');
Route::get('/first-aid/success', [FirstAidController::class, 'success'])->name('first_aid.success');
Route::get('/api/first-aid-records/{record}', [FirstAidController::class, 'getRecord'])->name('api.first_aid.get');
Route::get('/first-aid/{record}/download-pdf', [FirstAidController::class, 'downloadPdf'])->name('first_aid.download_pdf');

// ─── Public Support Request Route ──────────────────────────────────────
Route::post('/support/public-request', [\App\Http\Controllers\SupportController::class, 'storePublicRequest'])->name('support.public_request');

// ─── Guest Routes (unauthenticated) ─────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
});

// ─── Pending User Screen (authenticated but not approved) ──────────────
Route::middleware('auth')->group(function () {
    Route::get('/pending-user', function () {
        return view('pending-user');
    })->name('pending-user');

    Route::get('/pending', function () {
        return view('pending-user');
    })->name('pending');

    Route::post('/retry-application', [UserApprovalController::class, 'retryApplication'])->name('retry-application');
});

// ─── Super Admin Approval Panel (no approval_status check) ─────────────
Route::middleware(['auth', 'role:super_admin', 'prevent_back'])->group(function () {
    Route::get('/user-approval/dashboard', [UserApprovalController::class, 'dashboard'])->name('user-approval.dashboard');
    Route::get('/user-approval/audit-logs', [UserApprovalController::class, 'auditLogs'])->name('user-approval.audit_logs');
    Route::get('/user-approval/pending', [UserApprovalController::class, 'pending'])->name('user-approval.pending');
    Route::get('/user-approval/users', [UserApprovalController::class, 'index'])->name('user-approval.index');
    Route::get('/user-approval/{user}', [UserApprovalController::class, 'show'])->name('user-approval.show');
    Route::put('/user-approval/{user}', [UserApprovalController::class, 'show']); // Handle stray PUT requests
    Route::post('/user-approval/{user}/approve', [UserApprovalController::class, 'approve'])->name('user-approval.approve');
    Route::post('/user-approval/{user}/reject', [UserApprovalController::class, 'reject'])->name('user-approval.reject');
    Route::post('/user-approval/{user}/assign-role', [UserApprovalController::class, 'assignRole'])->name('user-approval.assign_role');
    Route::get('/api/audit-detail', [UserApprovalController::class, 'getAuditDetail'])->name('api.audit_detail');
});

// Profile routes (requires auth but NOT approval_status to allow profile updates)
Route::middleware(['auth', 'prevent_back'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'showChangePassword'])->name('profile.change-password');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// ─── Protected Routes (requires authentication + approval) ──────────────
Route::middleware(['auth', 'approval_status', 'prevent_back'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:view_dashboard');

    Route::get('/dashboards/supervisor', [DashboardController::class, 'supervisor'])->name('dashboards.supervisor')->middleware('role:supervisor', 'permission:view_dashboard');
    Route::get('/api/supervisor-dashboard-data', [DashboardController::class, 'supervisorDashboardData'])->name('api.supervisor_dashboard_data')->middleware('role:supervisor');

    // HR Dashboard API for real-time updates
    Route::get('/api/hr-dashboard-data', [DashboardController::class, 'hrDashboardData'])->name('api.hr_dashboard_data')->middleware('permission:view_dashboard');

    // PMD Dashboard API for real-time updates
    Route::get('/api/pmd-dashboard-data', [DashboardController::class, 'pmdDashboardData'])->name('api.pmd_dashboard_data')->middleware('role:pmd');

    // Super Admin Dashboard API for real-time updates
    Route::get('/api/super-admin-dashboard-data', [DashboardController::class, 'superAdminDashboardData'])->name('api.super_admin_dashboard_data')->middleware('role:super_admin');

    // Supervisor Routes
    Route::middleware(['role:supervisor', 'permission:view_incidents'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/incidents/all', [DashboardController::class, 'supervisorAllIncidents'])->name('all_incidents');
        Route::get('/incidents/by-month/{month}', [DashboardController::class, 'getIncidentsByMonth'])->name('incidents_by_month');
        Route::get('/reports/return-to-work-tracking', [DashboardController::class, 'supervisorReturnToWorkTracking'])->name('return_to_work_tracking');
        Route::get('/reports/compliance-metrics', [DashboardController::class, 'supervisorComplianceMetrics'])->name('compliance_metrics');
        Route::get('/reports/monthly-summary', [DashboardController::class, 'supervisorMonthlySummary'])->name('monthly_summary');
    });

    // Unified HR Dashboard for all HR roles
    Route::get('/dashboards/hr', [DashboardController::class, 'hrDashboard'])->name('dashboards.hr')->middleware('permission:view_dashboard');

    Route::get('/dashboards/district-hr', [DashboardController::class, 'districtHr'])->name('dashboards.district_hr')->middleware('role:district_hr', 'permission:view_dashboard');

    Route::get('/dashboards/hospital-hr', [DashboardController::class, 'hospitalHr'])->name('dashboards.hospital_hr')->middleware('role:hospital_hr', 'permission:view_dashboard');

    Route::get('/dashboards/central-hr', [DashboardController::class, 'centralHr'])->name('dashboards.central_hr')->middleware('role:central_hr', 'permission:view_dashboard');

    Route::get('/dashboards/dmo', [DashboardController::class, 'clinicalExecutivePanel'])->name('dashboards.dmo')->middleware('role:dmo', 'permission:view_dashboard');

    Route::get('/clinical-executive/dashboard', [DashboardController::class, 'clinicalExecutivePanel'])->name('dashboards.clinical_executive')->middleware('role:dmo,medical_superintendent,cmo', 'permission:view_dashboard');

    // DMO-Specific Dashboards
    Route::middleware(['role:dmo', 'permission:view_dashboard'])->prefix('dashboards/dmo')->name('dashboards.dmo.')->group(function () {
        Route::get('/medical-review', [DashboardController::class, 'dmoMedicalReview'])->name('medical_review');
        Route::get('/reports-analytics', [DashboardController::class, 'dmoReportsAnalytics'])->name('reports_analytics');
        Route::get('/compliance', [DashboardController::class, 'dmoCompliance'])->name('compliance');
        Route::get('/safety-analytics', [DashboardController::class, 'dmoSafetyAnalytics'])->name('safety_analytics');
    });

    // CMO-Specific Dashboards
    Route::middleware(['role:cmo', 'permission:view_dashboard'])->prefix('dashboards/cmo')->name('dashboards.cmo.')->group(function () {
        Route::get('/all-cases', [DashboardController::class, 'cmoAllCases'])->name('all_cases');
        Route::get('/pending-cases', [DashboardController::class, 'cmoPendingCases'])->name('pending_cases');
    });

    // Executive Review Routes (Two-Stage Process)
    Route::get('/executive/review/{incident}', [DashboardController::class, 'executiveReviewCase'])->name('executive.review_case')->middleware('role:dmo,medical_superintendent,cmo');
    Route::match(['get', 'post'], '/executive/recommendation/{incident}', [DashboardController::class, 'executiveRecommendation'])->name('executive.recommendation')->middleware('role:dmo,medical_superintendent,cmo');
    Route::post('/executive/submit-recommendation/{incident}', [DashboardController::class, 'submitExecutiveRecommendation'])->name('executive.submit_recommendation')->middleware('role:dmo,medical_superintendent,cmo');
    Route::post('/executive/send-letter-email/{incident}', [DashboardController::class, 'sendLetterEmail'])->name('executive.send_letter_email')->middleware('role:dmo,medical_superintendent,cmo');
    
    // Case Dossier with All Findings (read-only comprehensive view)
    Route::get('/case-dossier/{incident}', [DashboardController::class, 'caseDossierWithFindings'])->name('case.dossier_with_findings')->middleware('role:dmo,medical_superintendent,cmo,pmd');

    Route::get('/debug/case/{reference}', [DashboardController::class, 'debugCase'])->name('debug.case');
    Route::get('/debug/pmd-cases', [DashboardController::class, 'debugPMDCases'])->name('debug.pmd_cases');

    Route::get('/dashboards/pmd', [DashboardController::class, 'pmd'])->name('dashboards.pmd')->middleware('role:pmd', 'permission:view_dashboard');
    Route::get('/pmd/pending-cases', [DashboardController::class, 'pmdPendingCases'])->name('pmd.pending_cases')->middleware('role:pmd');

    // PMD Review Routes
    Route::get('/pmd/review/{incident}', [DashboardController::class, 'pmdReviewCase'])->name('pmd.review_case')->middleware('role:pmd');
    Route::post('/pmd/submit-review/{incident}', [DashboardController::class, 'submitPmdReview'])->name('pmd.submit_review')->middleware('role:pmd');

    Route::get('/dashboards/mohhc-hr', [DashboardController::class, 'mohhcHr'])->name('dashboards.mohhc_hr')->middleware('role:mohhc_hr', 'permission:view_dashboard');

    Route::get('/dashboards/labour-social', [DashboardController::class, 'labourSocial'])->name('dashboards.labour_social')->middleware('role:labour_social', 'permission:view_dashboard');

    Route::get('/dashboards/medical-board', [DashboardController::class, 'medicalBoard'])->name('dashboards.medical_board')->middleware('role:medical_board', 'permission:view_dashboard');

    Route::get('/medical-board/dashboard-redirect', [\App\Http\Controllers\MedicalBoardController::class, 'dashboard'])->name('medical_board.dashboard_redirect')->middleware('role:medical_board,pmd,cmo', 'permission:manage_incidents');

    Route::get('/dashboards/hsc-conditions', [DashboardController::class, 'hscConditions'])->name('dashboards.hsc_conditions')->middleware('role:hsc_conditions', 'permission:view_dashboard');

    Route::get('/dashboards/super-admin', [DashboardController::class, 'superAdmin'])->name('dashboards.super_admin')->middleware('role:super_admin', 'permission:view_dashboard');
    
    // API endpoint for live injury types data
    Route::get('/api/injury-types-data', [DashboardController::class, 'getInjuryTypesData'])->name('api.injury_types_data')->middleware('role:super_admin');

    // API endpoint for districts by province
    Route::get('/api/districts', [DashboardController::class, 'getDistrictsByProvince'])->name('api.districts_by_province')->middleware('role:super_admin');

    // Incident Management Routes (Super Admin)
    Route::middleware(['role:super_admin'])->prefix('incidents')->name('incidents.')->group(function () {
        Route::get('/all', [DashboardController::class, 'allIncidents'])->name('all');
        Route::get('/active-workflows', [DashboardController::class, 'activeWorkflows'])->name('active_workflows');
        Route::get('/hotspot-mapping', [DashboardController::class, 'incidentHotspotMapping'])->name('hotspot_mapping');
        Route::get('/closed-resolved', [DashboardController::class, 'closedResolved'])->name('closed_resolved');
        Route::get('/rejected', [DashboardController::class, 'rejectedCases'])->name('rejected');
        Route::get('/search', [DashboardController::class, 'searchIncidents'])->name('search_admin');
        Route::get('/statutory-risk', [DashboardController::class, 'statutoryRiskCases'])->name('statutory_risk');
    });

    // Reports & Analytics Routes (Super Admin)
    Route::middleware(['role:super_admin'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/national', [DashboardController::class, 'nationalReports'])->name('national');
        Route::get('/provincial-summary', [DashboardController::class, 'provincialSummary'])->name('provincial_summary');
        Route::get('/monthly-statistics', [DashboardController::class, 'monthlyStatistics'])->name('monthly_statistics');
        Route::get('/export-data', [DashboardController::class, 'exportData'])->name('export_data');
    });

    // System Configuration Routes (Super Admin)
    Route::middleware(['role:super_admin'])->prefix('system')->name('system.')->group(function () {
        Route::get('/facilities', [DashboardController::class, 'manageFacilities'])->name('facilities');
        Route::put('/facilities/{facility}', [DashboardController::class, 'updateFacility'])->name('facilities.update');
        Route::get('/departments', [DashboardController::class, 'manageDepartments'])->name('departments');
        Route::post('/departments', [DashboardController::class, 'storeDepartment'])->name('departments.store');
        Route::put('/departments/{department}', [DashboardController::class, 'updateDepartment'])->name('departments.update');
        Route::get('/designations', [DashboardController::class, 'manageDesignations'])->name('designations');
        Route::post('/designations', [DashboardController::class, 'storeDesignation'])->name('designations.store');
        Route::put('/designations/{designation}', [DashboardController::class, 'updateDesignation'])->name('designations.update');
        Route::get('/designations', [DashboardController::class, 'manageDesignations'])->name('designations');
        Route::get('/organizations', [DashboardController::class, 'manageOrganizations'])->name('organizations');
        Route::get('/provinces-districts', [DashboardController::class, 'manageProvincesDistricts'])->name('provinces_districts');
        Route::get('/roles', [DashboardController::class, 'manageRoles'])->name('roles');
        Route::post('/roles', [DashboardController::class, 'storeRole'])->name('roles.store');
        Route::get('/permissions', [DashboardController::class, 'managePermissions'])->name('permissions');
        Route::get('/role-permissions', [DashboardController::class, 'manageRolePermissions'])->name('role_permissions');
        Route::post('/role-permissions', [DashboardController::class, 'storeRolePermission'])->name('role_permissions.store');
        Route::delete('/role-permissions/{role}/{permission}', [DashboardController::class, 'deleteRolePermission'])->name('role_permissions.delete');
        Route::get('/thresholds', [DashboardController::class, 'manageThresholds'])->name('thresholds');
        Route::get('/it-support-tickets', [DashboardController::class, 'itSupportTickets'])->name('it_support_tickets');
        Route::get('/dashboard-mappings', [\App\Http\Controllers\SuperAdmin\DashboardMappingController::class, 'index'])->name('dashboard_mappings.index');
        Route::get('/dashboard-mappings/create', [\App\Http\Controllers\SuperAdmin\DashboardMappingController::class, 'create'])->name('dashboard_mappings.create');
        Route::post('/dashboard-mappings', [\App\Http\Controllers\SuperAdmin\DashboardMappingController::class, 'store'])->name('dashboard_mappings.store');
        Route::get('/dashboard-mappings/{mapping}/edit', [\App\Http\Controllers\SuperAdmin\DashboardMappingController::class, 'edit'])->name('dashboard_mappings.edit');
        Route::put('/dashboard-mappings/{mapping}', [\App\Http\Controllers\SuperAdmin\DashboardMappingController::class, 'update'])->name('dashboard_mappings.update');
        Route::delete('/dashboard-mappings/{mapping}', [\App\Http\Controllers\SuperAdmin\DashboardMappingController::class, 'destroy'])->name('dashboard_mappings.destroy');
        Route::post('/dashboard-mappings/{mapping}/toggle', [\App\Http\Controllers\SuperAdmin\DashboardMappingController::class, 'toggle'])->name('dashboard_mappings.toggle');
    });

    // Security & Audit Routes (Super Admin)
    Route::middleware(['role:super_admin'])->prefix('security')->name('security.')->group(function () {
        Route::get('/user-activity-logs', [DashboardController::class, 'userActivityLogs'])->name('user_activity_logs');
        Route::get('/system-access-logs', [DashboardController::class, 'systemAccessLogs'])->name('system_access_logs');
        Route::get('/failed-login-attempts', [DashboardController::class, 'failedLoginAttempts'])->name('failed_login_attempts');
    });

    // First Aid Review routes with permission checks
    Route::get('/first-aid/incident/{incident}', [FirstAidController::class, 'incident'])->name('first_aid.incident_records')->middleware('permission:view_first_aid');
    Route::post('/first-aid/{record}/approve', [FirstAidController::class, 'approve'])->name('first_aid.approve')->middleware('permission:manage_first_aid');
    Route::post('/first-aid/{record}/reject', [FirstAidController::class, 'reject'])->name('first_aid.reject')->middleware('permission:manage_first_aid');

    // HR Incident Review Routes (Stage 5)
    Route::get('/hr/incidents/pending-review', [IncidentController::class, 'hrPendingReview'])->name('incidents.hr_pending_review')->middleware('permission:view_incidents');
    Route::get('/hr/incidents/all', [DashboardController::class, 'hrAllIncidents'])->name('incidents.hr_all')->middleware('permission:view_incidents');
    Route::get('/hr/incidents/approved', [DashboardController::class, 'hrApprovedCases'])->name('incidents.hr_approved')->middleware('permission:view_incidents');
    Route::get('/hr/incidents/escalated-to-dmo', [DashboardController::class, 'hrEscalatedToDmo'])->name('incidents.hr_escalated_to_dmo')->middleware('permission:view_incidents');
    Route::get('/hr/incidents/rejected', [DashboardController::class, 'hrRejectedCases'])->name('incidents.hr_rejected')->middleware('permission:view_incidents');
    Route::get('/hr/incidents/resolved', [DashboardController::class, 'hrResolvedCases'])->name('incidents.hr_resolved')->middleware('permission:view_incidents');
    Route::get('/hr/reports/review-rate', [DashboardController::class, 'hrReviewRate'])->name('hr.review_rate')->middleware('permission:view_incidents');
    Route::get('/hr/reports/processing-time', [DashboardController::class, 'hrProcessingTime'])->name('hr.processing_time')->middleware('permission:view_incidents');
    Route::get('/hr/reports/compliance-metrics', [DashboardController::class, 'hrComplianceMetrics'])->name('hr.compliance_metrics')->middleware('permission:view_incidents');
    Route::get('/hr/reports/monthly-summary', [DashboardController::class, 'hrMonthlySummary'])->name('hr.monthly_summary')->middleware('permission:view_incidents');
    Route::get('/hr/reports/incident-facility', [DashboardController::class, 'hrIncidentByFacility'])->name('hr.incident_facility')->middleware('permission:view_incidents');
    Route::get('/hr/reports/incident-department', [DashboardController::class, 'hrIncidentByDepartment'])->name('hr.incident_department')->middleware('permission:view_incidents');
    Route::get('/hr/reports/compliance', [DashboardController::class, 'hrComplianceReport'])->name('hr.compliance_report')->middleware('permission:view_incidents');
    Route::get('/hr/reports/escalation', [DashboardController::class, 'hrEscalationReport'])->name('hr.escalation_report')->middleware('permission:view_incidents');
    Route::get('/hr/incidents/{incident}/dossier', [IncidentController::class, 'hrCaseDossier'])->name('incidents.hr_dossier')->middleware('permission:view_incidents');
    Route::post('/hr/incidents/{incident}/acknowledge-dossier', [IncidentController::class, 'acknowledgeCaseDossier'])->name('incidents.acknowledge_dossier');
    Route::get('/hr/incidents/{incident}/document-verification', [IncidentController::class, 'hrDocumentVerification'])->name('incidents.document_verification')->middleware('permission:view_incidents');
    Route::get('/hr/incidents/{incident}/leave-information', [IncidentController::class, 'leaveInformationCapture'])->name('incidents.leave_information_capture')->middleware('permission:view_incidents');
    Route::post('/hr/incidents/{incident}/leave-information', [\App\Http\Controllers\LeaveInformationController::class, 'store'])->name('incidents.leave_information_store')->middleware('permission:view_incidents');
    Route::post('/hr/incidents/send-sms', [IncidentController::class, 'sendSmsToReporter'])->name('incidents.send_sms')->middleware('permission:view_incidents');
    Route::post('/api/incidents/attachments/verify', [IncidentController::class, 'verifyAttachmentApi'])->name('incidents.verify_attachment');
    Route::post('/api/incidents/attachments/reject', [IncidentController::class, 'rejectAttachmentApi'])->name('incidents.reject_attachment');
    Route::post('/incidents/{incident}/attachments/{attachment}/verify', [IncidentController::class, 'verifyAttachment'])->name('incidents.verify_attachment_legacy');
    Route::post('/incidents/{incident}/attachments/{attachment}/reject', [IncidentController::class, 'rejectAttachment'])->name('incidents.reject_attachment_legacy');
    Route::get('/incidents/{incident}/attachments/{attachment}/view', [IncidentController::class, 'viewAttachment'])->name('incidents.view_attachment');
    Route::get('/incidents/{incident}/attachments/{attachment}/download', [IncidentController::class, 'downloadAttachment'])->name('incidents.download_attachment');
    Route::get('/incidents/witness/{witnessToken}/statement', [IncidentController::class, 'getWitnessStatement'])->name('incidents.get_witness_statement');
    Route::get('/incidents/first-aid/{recordId}', [IncidentController::class, 'getFirstAidRecord'])->name('incidents.first_aid_record');
    Route::get('/hr/incidents/{incident}/administrative-dispatch', [IncidentController::class, 'administrativeDispatch'])->name('incidents.administrative_dispatch')->middleware('permission:view_incidents');
    Route::post('/hr/incidents/{incident}/save-compliance-notes', [IncidentController::class, 'saveComplianceNotes'])->name('incidents.save_compliance_notes');
    Route::get('/hr/incidents/{incident}/download-dossier', [IncidentController::class, 'downloadCaseDossier'])->name('incidents.download_case_dossier');
    Route::post('/hr/incidents/{incident}/finalize-submission', [IncidentController::class, 'finalizeSubmission'])->name('incidents.finalize_submission');
    Route::post('/hr/incidents/{incident}/update-flags', [IncidentController::class, 'updateIncidentFlags'])->name('incidents.update_flags');
    Route::post('/hr/incidents/{incident}/compliance', [IncidentController::class, 'submitComplianceReview'])->name('incidents.submit_compliance');
    Route::get('/hr/incidents/{incident}', [IncidentController::class, 'hrView'])->name('incidents.hr_view')->middleware('permission:view_incidents');

    // Supervisor Incident Verification Routes (Stage 2)
    Route::get('/supervisor/incidents/{incident}', [IncidentController::class, 'supervisorView'])->name('incidents.supervisor_view')->middleware('role:supervisor', 'permission:view_incidents');
    Route::get('/supervisor/incidents/{incident}/case-dossier', [IncidentController::class, 'caseDossierView'])->name('incidents.case_dossier_view')->middleware('role:supervisor', 'permission:view_incidents');
    Route::post('/supervisor/incidents/{incident}/verify', [IncidentController::class, 'supervisorVerify'])->name('incidents.supervisor_verify')->middleware('role:supervisor', 'permission:manage_incidents');
    // Witness Management Routes - Task 10: Authorization and Security Checks
    // All routes require supervisor role AND assigned supervisor verification
    Route::middleware(['role:supervisor', 'permission:manage_incidents', 'witness.management.access'])->prefix('supervisor/incidents/{incident}')->name('incidents.')->group(function () {
        Route::get('/witness', [IncidentController::class, 'supervisorWitness'])->name('supervisor_witness');
        Route::get('/witnesses', [IncidentController::class, 'supervisorWitnessDashboard'])->name('supervisor_witness_dashboard');
        Route::post('/witness/generate-token', [IncidentController::class, 'generateWitnessToken'])->name('generate_witness_token');
        Route::post('/witness/token/{tokenId}', [IncidentController::class, 'updateWitnessToken'])->name('update_witness_token');
        Route::delete('/witness/token/{tokenId}', [IncidentController::class, 'deleteWitnessToken'])->name('delete_witness_token');
        Route::post('/witness/send-all', [IncidentController::class, 'sendAllWitnessSMS'])->name('send_all_witness_sms');
        Route::get('/witness/sms-preview', [IncidentController::class, 'getWitnessSmsPreview'])->name('witness_sms_preview');
        Route::post('/witness/token/{tokenId}/send', [IncidentController::class, 'sendWitnessToken'])->name('send_witness_token');
        Route::post('/witness/{tokenId}/reminder', [IncidentController::class, 'sendWitnessReminder'])->name('send_witness_reminder');
        Route::get('/witness/{tokenId}/download', [IncidentController::class, 'downloadWitnessStatement'])->name('download_witness_statement');
        Route::get('/witness-status', [IncidentController::class, 'getWitnessStatus'])->name('witness_status');
        Route::post('/witness/submit', [IncidentController::class, 'submitWitnessVerification'])->name('submit_witness_verification');
    });
    Route::get('/supervisor/incidents/{incident}/hod-report', [IncidentController::class, 'headOfDepartmentReport'])->name('incidents.hod_report')->middleware('role:supervisor', 'permission:manage_incidents');
    Route::post('/supervisor/incidents/{incident}/hod-report', [IncidentController::class, 'storeHeadOfDepartmentReport'])->name('incidents.store_hod_report')->middleware('role:supervisor', 'permission:manage_incidents');
    Route::get('/supervisor/incidents/{incident}/dossier-review', [IncidentController::class, 'supervisorDossierReview'])->name('incidents.supervisor_dossier_review')->middleware('role:supervisor', 'permission:manage_incidents');
    Route::get('/supervisor/incidents/{incident}/generate-dossier-pdf', [IncidentController::class, 'generateSupervisorDossierPDF'])->name('incidents.generate_supervisor_dossier_pdf')->middleware('permission:view_incidents');
    Route::post('/supervisor/incidents/{incident}/submit-to-hr', [IncidentController::class, 'submitDossierToHR'])->name('incidents.submit_dossier_to_hr')->middleware('role:supervisor', 'permission:manage_incidents');

    // ─── Return-to-Work Management Routes (HR) ───────────────────────────────
    Route::middleware(['role:hr', 'permission:manage_incidents'])->prefix('hr')->name('hr.')->group(function () {
        Route::get('/rtw/{incident}/edit', [ReturnToWorkController::class, 'edit'])->name('rtw.edit');
        Route::put('/rtw/{incident}', [ReturnToWorkController::class, 'update'])->name('rtw.update');
        Route::post('/rtw/{incident}/reviewed', [ReturnToWorkController::class, 'markReviewed'])->name('rtw.mark_reviewed');
        Route::post('/rtw/{incident}/complete', [ReturnToWorkController::class, 'complete'])->name('rtw.complete');
        Route::post('/rtw/{incident}/terminate', [ReturnToWorkController::class, 'terminate'])->name('rtw.terminate');
    });
    Route::post('/supervisor/incidents/{incident}/witness/submit', [IncidentController::class, 'submitWitnessVerification'])->name('incidents.submit_witness_verification')->middleware('role:supervisor', 'permission:manage_incidents');

    // Supervisor API Routes
    Route::get('/api/supervisor/incidents/{incident}/dossier', [IncidentController::class, 'supervisorDossierApi'])->name('api.supervisor.dossier')->middleware('role:supervisor', 'permission:view_incidents');

    // IT Support Ticketing Routes
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/ticket/create', [\App\Http\Controllers\SupportController::class, 'createTicket'])->name('create_ticket');
        Route::post('/ticket', [\App\Http\Controllers\SupportController::class, 'storeTicket'])->name('store_ticket');
        
        // Originator can view their tickets
        Route::get('/my-tickets', [\App\Http\Controllers\TicketController::class, 'myTickets'])->name('my_tickets');
        
        // Super admin ticket dashboard
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/tickets', [\App\Http\Controllers\SuperAdminTicketController::class, 'index'])->name('tickets.index');
            Route::put('/ticket/{ticket}/status', [\App\Http\Controllers\TicketController::class, 'updateStatus'])->name('ticket.update_status');
            Route::post('/ticket/{ticket}/reply', [\App\Http\Controllers\TicketController::class, 'storeReply'])->name('ticket.store_reply');
        });

        // Ticket details (both originator and super admin can view)
        Route::get('/ticket/{ticket}', [\App\Http\Controllers\TicketController::class, 'show'])->name('ticket.show');
        Route::get('/ticket/{ticket}/replies-json', [\App\Http\Controllers\TicketController::class, 'getReplies'])->name('ticket.replies_json');
    });

    // API Routes for notifications
    Route::get('/api/notifications', [DashboardController::class, 'getNotifications'])->name('api.notifications');
    Route::post('/api/notifications/{notification}/read', [DashboardController::class, 'markNotificationRead'])->name('api.mark_notification_read');

    // API Routes for Incident Hotspot Mapping
    Route::get('/api/map/provinces', [DashboardController::class, 'getProvinces'])->name('api.map.provinces')->middleware('role:super_admin');
    Route::get('/api/map/districts/{provinceId}', [DashboardController::class, 'getDistricts'])->name('api.map.districts')->middleware('role:super_admin');
    Route::get('/api/map/facilities/{districtId}', [DashboardController::class, 'getFacilities'])->name('api.map.facilities')->middleware('role:super_admin');

    // ─── Medical Board Management Routes ────────────────────────────────
    Route::middleware(['role:medical_board,pmd,cmo', 'permission:manage_incidents'])->prefix('medical-board')->name('medical_board.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\MedicalBoardController::class, 'dashboard'])->name('dashboard');
        Route::get('/incidents/{incident}/review', [\App\Http\Controllers\MedicalBoardController::class, 'review'])->name('review');
    });

    // ─── Conditions of Service (CoS) Department Dashboard Routes ─────────
    Route::middleware(['role:hsc_conditions', 'permission:view_all_cases'])->prefix('cos-dashboard')->name('cos.')->group(function () {
        // Executive Dashboard
        Route::get('/', [\App\Http\Controllers\CoS\ExecutiveDashboardController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\CoS\ExecutiveDashboardController::class, 'export'])->name('export')->middleware('permission:export_data');

        // Case Flow Tracker
        Route::get('/case/{caseId}', [\App\Http\Controllers\CoS\CaseFlowTrackerController::class, 'show'])->name('case.show')->middleware('permission:view_timeline');
        Route::post('/case/{caseId}/monitoring-note', [\App\Http\Controllers\CoS\CaseFlowTrackerController::class, 'addMonitoringNote'])->name('case.add_note')->middleware('permission:add_monitoring_note');
        Route::post('/case/{caseId}/nudge', [\App\Http\Controllers\CoS\CaseFlowTrackerController::class, 'sendNudge'])->name('case.send_nudge')->middleware('permission:send_reminder');
        Route::post('/case/{caseId}/escalate', [\App\Http\Controllers\CoS\CaseFlowTrackerController::class, 'escalate'])->name('case.escalate')->middleware('permission:escalate_case');

        // Compliance Dashboard
        Route::get('/compliance/{caseId}', [\App\Http\Controllers\CoS\ComplianceDashboardController::class, 'show'])->name('compliance.show')->middleware('permission:run_compliance_checklist');
        Route::post('/compliance/{caseId}/save', [\App\Http\Controllers\CoS\ComplianceDashboardController::class, 'save'])->name('compliance.save')->middleware('permission:run_compliance_checklist');
        Route::get('/compliance-report', [\App\Http\Controllers\CoS\ComplianceDashboardController::class, 'report'])->name('compliance.report')->middleware('permission:run_compliance_checklist');
        Route::get('/compliance-report/export', [\App\Http\Controllers\CoS\ComplianceDashboardController::class, 'export'])->name('compliance.export')->middleware('permission:export_data');

        // Audit Log
        Route::get('/audit-log/{caseId}', [\App\Http\Controllers\CoS\AuditLogController::class, 'show'])->name('audit.show')->middleware('permission:view_audit_log');
        Route::get('/audit-log/{caseId}/export', [\App\Http\Controllers\CoS\AuditLogController::class, 'export'])->name('audit.export')->middleware('permission:export_data');
        Route::get('/audit-report', [\App\Http\Controllers\CoS\AuditLogController::class, 'report'])->name('audit.report')->middleware('permission:view_audit_log');
        Route::get('/audit-report/export', [\App\Http\Controllers\CoS\AuditLogController::class, 'exportReport'])->name('audit.export_report')->middleware('permission:export_data');

        // Notifications Hub
        Route::get('/notifications', [\App\Http\Controllers\CoS\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notificationId}/read', [\App\Http\Controllers\CoS\NotificationController::class, 'markAsRead'])->name('notifications.mark_read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\CoS\NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');
        Route::get('/notifications/filter', [\App\Http\Controllers\CoS\NotificationController::class, 'filter'])->name('notifications.filter');
        Route::get('/api/notifications', [\App\Http\Controllers\CoS\NotificationController::class, 'getNotifications'])->name('notifications.api');

        // Announcements
        Route::get('/announcements', [\App\Http\Controllers\CoS\AnnouncementController::class, 'index'])->name('announcements.index')->middleware('permission:broadcast_announcement');
        Route::get('/announcements/create', [\App\Http\Controllers\CoS\AnnouncementController::class, 'create'])->name('announcements.create')->middleware('permission:broadcast_announcement');
        Route::post('/announcements', [\App\Http\Controllers\CoS\AnnouncementController::class, 'store'])->name('announcements.store')->middleware('permission:broadcast_announcement');
        Route::get('/announcements/{announcement}', [\App\Http\Controllers\CoS\AnnouncementController::class, 'show'])->name('announcements.show')->middleware('permission:broadcast_announcement');
        Route::get('/announcements/{announcement}/edit', [\App\Http\Controllers\CoS\AnnouncementController::class, 'edit'])->name('announcements.edit')->middleware('permission:broadcast_announcement');
        Route::put('/announcements/{announcement}', [\App\Http\Controllers\CoS\AnnouncementController::class, 'update'])->name('announcements.update')->middleware('permission:broadcast_announcement');
        Route::delete('/announcements/{announcement}', [\App\Http\Controllers\CoS\AnnouncementController::class, 'destroy'])->name('announcements.destroy')->middleware('permission:broadcast_announcement');
        Route::get('/api/announcements/active-banner', [\App\Http\Controllers\CoS\AnnouncementController::class, 'getActiveBannerAnnouncements'])->name('announcements.active_banner');
    });
});