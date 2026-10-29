<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Add roster attribution fields
            $table->foreignId('worker_primary_facility_id')->nullable()->constrained('facilities')->onDelete('restrict');
            $table->foreignId('cost_center_facility_id')->nullable()->constrained('facilities')->onDelete('restrict');
            $table->foreignId('roster_assignment_id')->nullable()->constrained('roster_assignments')->onDelete('restrict');
            $table->boolean('is_rotational_staff')->default(false);

            // Add indexes for reporting queries
            $table->index('worker_primary_facility_id', 'idx_incidents_worker_primary_facility');
            $table->index('cost_center_facility_id', 'idx_incidents_cost_center_facility');
            $table->index('roster_assignment_id', 'idx_incidents_roster_assignment');
            $table->index('is_rotational_staff', 'idx_incidents_is_rotational_staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_incidents_worker_primary_facility');
            $table->dropIndex('idx_incidents_cost_center_facility');
            $table->dropIndex('idx_incidents_roster_assignment');
            $table->dropIndex('idx_incidents_is_rotational_staff');

            // Drop foreign key constraints
            $table->dropForeign(['worker_primary_facility_id']);
            $table->dropForeign(['cost_center_facility_id']);
            $table->dropForeign(['roster_assignment_id']);

            // Drop columns
            $table->dropColumn([
                'worker_primary_facility_id',
                'cost_center_facility_id',
                'roster_assignment_id',
                'is_rotational_staff'
            ]);
        });
    }
};
