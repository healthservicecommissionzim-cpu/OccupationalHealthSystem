<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use App\Models\Incident;

// Get John Mashizha
$john = User::where('email', 'john.mashizha@health.gov.zw')->first();
echo "=== John Mashizha ===\n";
echo "ID: " . ($john->id ?? 'NOT FOUND') . "\n";
echo "Email: " . ($john->email ?? 'N/A') . "\n";

if ($john) {
    $pending = Incident::where('routed_to_hr_user_id', $john->id)
        ->where('status', 'VERIFIED_SUPERVISOR')
        ->get();
    
    echo "Total pending incidents: " . $pending->count() . "\n\n";
    
    foreach ($pending as $incident) {
        echo "- " . $incident->reference_number . ": " . $incident->first_name . " " . $incident->surname . "\n";
    }
}
