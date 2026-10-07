<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Add support for RESIDENT_LOCAL_HR_REVIEW routing (District Hospital pathway)
            if (!Schema::hasColumn('incidents', 'routed_to_district_hr_user_id')) {
                $table->unsignedBigInteger('routed_to_district_hr_user_id')
                    ->nullable()
                    ->after('routed_to_dmo_user_id')
                    ->comment('District HR assigned for resident local HR review (District Hospital pathway)');
            }
        });

        // Update hr_route_type comment to include new RESIDENT_LOCAL_HR_REVIEW option (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE incidents MODIFY COLUMN hr_route_type VARCHAR(255) 
                COMMENT 'RESIDENT_LOCAL_HR_REVIEW | DISTRICT_HR_REVIEW | HOSPITAL_HR_REVIEW | CENTRAL_HR_REVIEW'
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            if (Schema::hasColumn('incidents', 'routed_to_district_hr_user_id')) {
                $table->dropColumn('routed_to_district_hr_user_id');
            }
        });

        // Revert hr_route_type comment (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE incidents MODIFY COLUMN hr_route_type VARCHAR(255) 
                COMMENT 'DISTRICT_HR_REVIEW | HOSPITAL_HR_REVIEW | CENTRAL_HR_REVIEW'
            ");
        }
    }
};
