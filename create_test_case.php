<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get existing case data
$existingCase = \App\Models\Incident::find(2);

echo "=== EXISTING CASE DATA ===\n";
echo "First Name: " . $existingCase->first_name . "\n";
echo "Surname: " . $existingCase->surname . "\n";
echo "Facility ID: " . $existingCase->facility_id . "\n";
echo "District ID: " . $existingCase->district_id . "\n";
echo "Designation ID: " . $existingCase->designation_id . "\n";
echo "Department ID: " . $existingCase->department_id . "\n";
echo "Province ID: " . $existingCase->province_id . "\n";
echo "Facility Type ID: " . $existingCase->facility_type_id . "\n";
echo "Incident Date: " . $existingCase->incident_date . "\n";
echo "Supervisor Designation ID: " . $existingCase->supervisor_designation_id . "\n";

// Create new case with similar data
$newCase = \App\Models\Incident::create([
    'reference_number' => 'HSC-2026-000002',
    'first_name' => 'Test',
    'surname' => 'Employee',
    'designation_id' => $existingCase->designation_id,
    'department_id' => $existingCase->department_id,
    'id_number' => '12345678',
    'ec_number' => 'EC123456',
    'home_address' => '123 Test Street',
    'phone_number' => '0771234567',
    'email' => 'test@example.com',
    'incident_date' => now()->subDays(5)->toDateString(),
    'incident_time' => '14:30',
    'incident_description' => 'Test incident for administrative workflow',
    'facility_type_id' => $existingCase->facility_type_id,
    'province_id' => $existingCase->province_id,
    'district_id' => $existingCase->district_id,
    'facility_id' => $existingCase->facility_id,
    'supervisor_designation_id' => $existingCase->supervisor_designation_id,
    'supervisor_name' => 'John Supervisor',
    'status' => 'VERIFIED_SUPERVISOR',
    'hr_route_type' => 'DISTRICT_HR_REVIEW',
    'escalated_to_hr_at' => now(),
    'hr_dossier_acknowledged_at' => now(),
    'hr_dossier_acknowledged_by' => 1,
    'hr_compliance_notes' => 'Test compliance notes for administrative workflow testing',
    'hr_compliance_notes_saved_at' => now(),
]);

echo "\n=== NEW TEST CASE CREATED ===\n";
echo "Reference: " . $newCase->reference_number . "\n";
echo "ID: " . $newCase->id . "\n";
echo "Status: " . $newCase->status . "\n";
echo "\nYou can now access it at:\n";
echo "http://127.0.0.1:8000/hr/incidents/" . $newCase->id . "/administrative-dispatch\n";
