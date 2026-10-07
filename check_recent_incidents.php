<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$incidents = \App\Models\Incident::orderBy('id', 'desc')->limit(10)->get();
echo "Recent Incidents and their Incident Types:\n";
echo "=".str_repeat("=", 80)."\n";
foreach ($incidents as $incident) {
    echo sprintf(
        "%-15s | ID Type: %-3s | Type: %-30s | Created: %s\n",
        $incident->reference_number,
        $incident->incident_type_id ?? 'NULL',
        $incident->incidentType?->name ?? 'N/A',
        $incident->created_at->format('d M Y')
    );
}
?>
