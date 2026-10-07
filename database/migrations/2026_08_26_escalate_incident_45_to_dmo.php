<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find incident 45
        $incident = DB::table('incidents')->where('id', 45)->first();
        
        if ($incident && $incident->status === 'HR_APPROVED') {
            // Get the facility
            $facility = DB::table('facilities')->where('id', $incident->facility_id)->first();
            
            if ($facility) {
                // Try to find DMO in the same district
                $dmo = DB::table('users')
                    ->join('role_user', 'users.id', '=', 'role_user.user_id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.name', 'dmo')
                    ->where('users.district_id', $facility->district_id)
                    ->select('users.id')
                    ->first();
                
                // Fallback: find any DMO
                if (!$dmo) {
                    $dmo = DB::table('users')
                        ->join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->where('roles.name', 'dmo')
                        ->select('users.id')
                        ->first();
                }
                
                if ($dmo) {
                    // Update incident to escalate to DMO
                    DB::table('incidents')->where('id', 45)->update([
                        'status' => 'ESCALATED_TO_DMO',
                        'routed_to_dmo_user_id' => $dmo->id,
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert incident 45 back to HR_APPROVED
        DB::table('incidents')->where('id', 45)->update([
            'status' => 'HR_APPROVED',
            'routed_to_dmo_user_id' => null,
            'updated_at' => now(),
        ]);
    }
};
