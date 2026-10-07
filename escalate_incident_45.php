<?php
// Quick escalation script for incident 45
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Incident;
use App\Models\User;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Get the incident
$incident = Incident::findOrFail(45);

echo "═══════════════════════════════════════════════════════════════\n";
echo "INCIDENT 45 ESCALATION CHECK\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "📋 CURRENT STATE:\n";
echo "  Reference: {$incident->reference_number}\n";
echo "  Status: {$incident->status}\n";
echo "  HR Route Type: {$incident->hr_route_type}\n";
echo "  HR Compliance Status: {$incident->hr_compliance_status}\n";
echo "  Routed to DMO User ID: {$incident->routed_to_dmo_user_id}\n\n";

if ($incident->facility) {
    echo "📍 FACILITY INFO:\n";
    echo "  Facility: {$incident->facility->name}\n";
    echo "  District: {$incident->facility->district?->name} (ID: {$incident->facility->district_id})\n";
    echo "  District ID for search: {$incident->facility->district_id}\n\n";
}

// Check if it's HR_APPROVED and needs escalation
if ($incident->status === 'HR_APPROVED' && $incident->hr_compliance_status === 'APPROVED') {
    echo "⚠️  CASE IS HR_APPROVED BUT NOT ESCALATED TO DMO\n\n";
    
    if ($incident->hr_route_type === 'DISTRICT_HR_REVIEW') {
        echo "🔍 FINDING DMO FOR ESCALATION:\n";
        
        // Try to find DMO in the same district
        $dmo = User::whereHas('roles', fn($q) => $q->where('name', 'dmo'))
            ->where('district_id', $incident->facility->district_id)
            ->first();
        
        if (!$dmo) {
            echo "  ❌ No DMO found in district {$incident->facility->district_id}\n";
            echo "  🔄 Trying fallback: finding any available DMO...\n";
            $dmo = User::whereHas('roles', fn($q) => $q->where('name', 'dmo'))
                ->first();
        }
        
        if ($dmo) {
            echo "  ✅ DMO FOUND:\n";
            echo "     Name: {$dmo->name}\n";
            echo "     ID: {$dmo->id}\n";
            echo "     Email: {$dmo->email}\n";
            echo "     Phone: {$dmo->phone_number}\n";
            echo "     District: {$dmo->district?->name} (ID: {$dmo->district_id})\n\n";
            
            echo "🚀 ESCALATING CASE TO DMO...\n\n";
            
            $incident->update([
                'status' => 'ESCALATED_TO_DMO',
                'routed_to_dmo_user_id' => $dmo->id,
                'hr_review_completed_at' => now(),
                'hr_review_completed_by_user_id' => $incident->hr_reviewed_by ?? 1,
            ]);
            
            echo "✅ ESCALATION COMPLETE!\n";
            echo "   Status: {$incident->status}\n";
            echo "   Assigned to DMO: {$dmo->name} (ID: {$dmo->id})\n";
            echo "   Escalated at: " . now()->format('Y-m-d H:i:s') . "\n";
        } else {
            echo "  ❌ NO DMO FOUND IN SYSTEM\n";
            echo "  Please create a DMO user first.\n";
        }
    } else {
        echo "⚠️  HR route type is: {$incident->hr_route_type}\n";
        echo "    Expected: DISTRICT_HR_REVIEW for DMO escalation\n";
    }
} else {
    echo "✅ CASE IS ALREADY IN CORRECT STATE:\n";
    echo "   Status: {$incident->status}\n";
    if ($incident->routed_to_dmo_user_id) {
        $dmo = User::find($incident->routed_to_dmo_user_id);
        echo "   Assigned DMO: {$dmo->name} (ID: {$dmo->id})\n";
    }
}

echo "\n═══════════════════════════════════════════════════════════════\n";
?>
