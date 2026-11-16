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
        // Get existing indexes
        $existingIndexes = $this->getExistingIndexes('incidents');
        
        Schema::table('incidents', function (Blueprint $table) use ($existingIndexes) {
            // Add indexes for frequently queried columns (only if they don't exist)
            if (!in_array('idx_incidents_status', $existingIndexes)) {
                $table->index('status', 'idx_incidents_status');
            }
            if (!in_array('idx_incidents_created_at', $existingIndexes)) {
                $table->index('created_at', 'idx_incidents_created_at');
            }
            if (!in_array('idx_incidents_province_id', $existingIndexes)) {
                $table->index('province_id', 'idx_incidents_province_id');
            }
            if (!in_array('idx_incidents_district_id', $existingIndexes)) {
                $table->index('district_id', 'idx_incidents_district_id');
            }
            if (!in_array('idx_incidents_facility_id', $existingIndexes)) {
                $table->index('facility_id', 'idx_incidents_facility_id');
            }
            if (!in_array('idx_incidents_incident_type_id', $existingIndexes)) {
                $table->index('incident_type_id', 'idx_incidents_incident_type_id');
            }
            
            // Composite index for common query patterns
            if (!in_array('idx_incidents_created_status', $existingIndexes)) {
                $table->index(['created_at', 'status'], 'idx_incidents_created_status');
            }
            if (!in_array('idx_incidents_province_status', $existingIndexes)) {
                $table->index(['province_id', 'status'], 'idx_incidents_province_status');
            }
            if (!in_array('idx_incidents_district_status', $existingIndexes)) {
                $table->index(['district_id', 'status'], 'idx_incidents_district_status');
            }
        });
    }

    /**
     * Get existing indexes for a table
     */
    private function getExistingIndexes(string $tableName): array
    {
        $indexes = DB::select("SHOW INDEX FROM {$tableName}");
        return array_map(fn($index) => $index->Key_name, $indexes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get existing indexes before dropping
        $existingIndexes = $this->getExistingIndexes('incidents');
        
        Schema::table('incidents', function (Blueprint $table) use ($existingIndexes) {
            // Drop indexes only if they exist
            if (in_array('idx_incidents_status', $existingIndexes)) {
                $table->dropIndex('idx_incidents_status');
            }
            if (in_array('idx_incidents_created_at', $existingIndexes)) {
                $table->dropIndex('idx_incidents_created_at');
            }
            if (in_array('idx_incidents_province_id', $existingIndexes)) {
                $table->dropIndex('idx_incidents_province_id');
            }
            if (in_array('idx_incidents_district_id', $existingIndexes)) {
                $table->dropIndex('idx_incidents_district_id');
            }
            if (in_array('idx_incidents_facility_id', $existingIndexes)) {
                $table->dropIndex('idx_incidents_facility_id');
            }
            if (in_array('idx_incidents_incident_type_id', $existingIndexes)) {
                $table->dropIndex('idx_incidents_incident_type_id');
            }
            if (in_array('idx_incidents_created_status', $existingIndexes)) {
                $table->dropIndex('idx_incidents_created_status');
            }
            if (in_array('idx_incidents_province_status', $existingIndexes)) {
                $table->dropIndex('idx_incidents_province_status');
            }
            if (in_array('idx_incidents_district_status', $existingIndexes)) {
                $table->dropIndex('idx_incidents_district_status');
            }
        });
    }
};
