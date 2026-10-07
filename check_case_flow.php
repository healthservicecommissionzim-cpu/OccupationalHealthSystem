<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$incident = \App\Models\Incident::where('reference_number', 'HSC-2026-000045')->first();

if ($incident) {
    echo "=== CASE FLOW FOR HSC-2026-000045 ===\n\n";
    
    echo "1. INITIAL SUBMISSION:\n";
    echo "   - Created At: " . $incident->created_at . "\n";
    echo "   - Initial Status: INCIDENT_REPORTED\n\n";
    
    echo "2. SUPERVISOR VERIFICATION:\n";
    echo "   - Supervisor Verified At: " . ($incident->supervisor_verified_at ?? 'Not Yet') . "\n\n";
    
    echo "3. HOD REPORT SUBMISSION:\n";
    echo "   - HOD Report Submitted At: " . ($incident->hod_report_submitted_at ?? 'Not Yet') . "\n";
    echo "   - HOD Report Submitted By: " . ($incident->hod_report_submitted_by ?? 'N/A') . "\n\n";
    
    echo "4. HR REVIEW:\n";
    echo "   - HR Assigned: " . ($incident->routed_to_hr_user_id ?? 'Not Assigned') . "\n";
    echo "   - HR Dossier Acknowledged At: " . ($incident->hr_dossier_acknowledged_at ?? 'Not Yet') . "\n";
    echo "   - HR Reviewed At: " . ($incident->hr_reviewed_at ?? 'Not Yet') . "\n";
    echo "   - HR Compliance Status: " . ($incident->hr_compliance_status ?? 'N/A') . "\n\n";
    
    echo "5. CMO/DMO REVIEW:\n";
    echo "   - Status: " . $incident->status . "\n";
    echo "   - Routed to CMO: " . ($incident->routed_to_cmo_user_id ?? 'Not Assigned') . "\n";
    echo "   - Routed to DMO: " . ($incident->routed_to_dmo_user_id ?? 'Not Assigned') . "\n";
    echo "   - Executive Audit Verified: " . ($incident->executive_audit_verified ? 'Yes' : 'No') . "\n";
    echo "   - Executive Audit Verified At: " . ($incident->executive_audit_verified_at ?? 'Not Yet') . "\n";
    echo "   - Executive Recommendation Submitted At: " . ($incident->executive_recommendation_submitted_at ?? 'Not Yet') . "\n\n";
    
    echo "6. PMD REVIEW:\n";
    echo "   - Routed to PMD: " . ($incident->routed_to_pmd_user_id ?? 'Not Assigned') . "\n";
    echo "   - PMD Status: " . ($incident->routed_to_pmd_status ?? 'N/A') . "\n\n";
    
    echo "7. SOCIAL SERVICES:\n";
    echo "   - Submitted to Social Services At: " . ($incident->submitted_to_social_services_at ?? 'Not Yet') . "\n";
    echo "   - Submission Status: " . ($incident->submission_status ?? 'N/A') . "\n";
    echo "   - Physical Handoff Timestamp: " . ($incident->physical_handoff_timestamp ?? 'Not Yet') . "\n";
    echo "   - Signed Manifest Uploaded At: " . ($incident->signed_manifest_uploaded_at ?? 'Not Yet') . "\n\n";
    
    echo "8. MEDICAL BOARD (if applicable):\n";
    echo "   - Total Injury Leave Days: " . ($incident->total_injury_leave_days ?? 'Not Set') . "\n";
    echo "   - Current Status: " . $incident->status . "\n";
    
} else {
    echo "Case HSC-2026-000045 not found\n";
}
