<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$incident = \Illuminate\Support\Facades\DB::table('incidents')
    ->where('reference_number', 'HSC-2026-000045')
    ->first();

if ($incident) {
    echo "Case Found!\n";
    echo "Reference: " . $incident->reference_number . "\n";
    echo "Status: " . $incident->status . "\n";
    echo "Assigned Supervisor ID: " . $incident->assigned_supervisor_id . "\n";
    
    // Get supervisor name
    $supervisor = \Illuminate\Support\Facades\DB::table('users')
        ->where('id', $incident->assigned_supervisor_id)
        ->first();
    
    if ($supervisor) {
        echo "Assigned Supervisor Name: " . $supervisor->name . "\n";
    }
    
    echo "\n--- All Incident Fields ---\n";
    foreach ((array)$incident as $key => $value) {
        echo "$key: $value\n";
    }
} else {
    echo "Case not found\n";
}
?>
