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
            // Add indexes for frequently queried columns
            $table->index('status', 'idx_incidents_status');
            $table->index('created_at', 'idx_incidents_created_at');
            $table->index('province_id', 'idx_incidents_province_id');
            $table->index('district_id', 'idx_incidents_district_id');
            $table->index('facility_id', 'idx_incidents_facility_id');
            $table->index('incident_type_id', 'idx_incidents_incident_type_id');
            
            // Composite index for common query patterns
            $table->index(['created_at', 'status'], 'idx_incidents_created_status');
            $table->index(['province_id', 'status'], 'idx_incidents_province_status');
            $table->index(['district_id', 'status'], 'idx_incidents_district_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex('idx_incidents_status');
            $table->dropIndex('idx_incidents_created_at');
            $table->dropIndex('idx_incidents_province_id');
            $table->dropIndex('idx_incidents_district_id');
            $table->dropIndex('idx_incidents_facility_id');
            $table->dropIndex('idx_incidents_incident_type_id');
            $table->dropIndex('idx_incidents_created_status');
            $table->dropIndex('idx_incidents_province_status');
            $table->dropIndex('idx_incidents_district_status');
        });
    }
};
