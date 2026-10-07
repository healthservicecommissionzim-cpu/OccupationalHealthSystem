<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\User;
use App\Models\Facility;
use App\Models\Department;
use App\Models\Designation;

$incidentTypes = IncidentType::all();
$reporters = User::limit(5)->get();
$facilities = Facility::limit(20)->get();
$departments = Department::limit(10)->get();
$designations = Designation::limit(10)->get();

echo "Creating test incidents...\n";
echo "Incident types: " . $incidentTypes->count() . "\n";
echo "Reporters: " . $reporters->count() . "\n";
echo "Facilities: " . $facilities->count() . "\n";

$count = 0;
$names = ['John Smith', 'Mary Johnson', 'Peter Brown', 'Sarah Williams', 'James Davis'];

foreach ($reporters as $idx => $reporter) {
    for ($i = 0; $i < 3; $i++) {
        $nameParts = explode(' ', $names[$idx % count($names)]);
        $facility = $facilities->random();
        
        Incident::create([
            'reference_number' => 'TEST-' . date('Y') . '-' . str_pad($count++, 5, '0', STR_PAD_LEFT),
            'first_name' => $nameParts[0],
            'surname' => $nameParts[1] ?? 'Doe',
            'id_number' => str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
            'ec_number' => 'EC-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'date_of_birth' => now()->subYears(rand(25, 60))->format('Y-m-d'),
            'marital_status' => 'Single',
            'home_address' => rand(1, 1000) . ' Test Street, Harare',
            'phone_number' => '263' . rand(100000000, 999999999),
            'email' => strtolower($nameParts[0]) . '@test.com',
            'reporter_id' => $reporter->id,
            'facility_id' => $facility->id,
            'facility_type_id' => $facility->facility_type_id ?? 1,
            'province_id' => $facility->district->province_id ?? 1,
            'district_id' => $facility->district_id ?? 1,
            'incident_type_id' => $incidentTypes->random()->id,
            'incident_date' => now()->subDays(rand(1, 90))->format('Y-m-d'),
            'incident_time' => now()->subHours(rand(1, 72))->format('H:i:s'),
            'incident_description' => 'Test incident - Sample description for testing',
            'department_id' => $departments->random()->id,
            'designation_id' => $designations->random()->id,
            'supervisor_designation_id' => $designations->random()->id,
            'supervisor_name' => 'Test Supervisor',
            'status' => 'PENDING_HOD_REVIEW',
        ]);
    }
}

echo "Created " . Incident::count() . " test incidents\n";

