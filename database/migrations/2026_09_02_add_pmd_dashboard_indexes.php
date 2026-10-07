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
            // Index for PMD dashboard queries
            $table->index(['status', 'routed_to_pmd_user_id', 'created_at'], 'idx_incidents_pmd_dashboard');
            $table->index(['status', 'facility_id', 'created_at'], 'idx_incidents_status_facility');
            $table->index('created_at', 'idx_incidents_created_at');
            $table->index('routed_to_pmd_user_id', 'idx_incidents_pmd_user');
        });

        Schema::table('incident_notifications', function (Blueprint $table) {
            // Index for notifications queries
            $table->index(['recipient_user_id', 'status', 'created_at'], 'idx_notifications_recipient_status');
        });

        Schema::table('facilities', function (Blueprint $table) {
            // Index for facility district lookups
            $table->index('district_id', 'idx_facility_district');
        });

        Schema::table('districts', function (Blueprint $table) {
            // Index for district province lookups
            $table->index('province_id', 'idx_district_province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropIndex('idx_incidents_pmd_dashboard');
            $table->dropIndex('idx_incidents_status_facility');
            $table->dropIndex('idx_incidents_created_at');
            $table->dropIndex('idx_incidents_pmd_user');
        });

        Schema::table('incident_notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notifications_recipient_status');
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->dropIndex('idx_facility_district');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropIndex('idx_district_province');
        });
    }
};
