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
        Schema::create('roster_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_roster_id')->constrained('staff_rosters')->onDelete('restrict');
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('restrict');
            $table->date('assignment_start_date');
            $table->date('assignment_end_date');
            $table->foreignId('cost_center_facility_id')->constrained('facilities')->onDelete('restrict');
            $table->enum('assignment_type', ['rotation', 'locum_contract', 'student_placement']);
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            // Indexes for performance
            $table->index('staff_roster_id', 'idx_roster_assignments_staff_roster_id');
            $table->index('facility_id', 'idx_roster_assignments_facility_id');
            $table->index(['assignment_start_date', 'assignment_end_date'], 'idx_roster_assignments_dates');
            $table->index('cost_center_facility_id', 'idx_roster_assignments_cost_center');
            $table->index('status', 'idx_roster_assignments_status');
            
            // Composite index for active assignment queries (performance critical)
            $table->index(
                ['facility_id', 'assignment_start_date', 'assignment_end_date', 'status'],
                'idx_roster_assignments_active_lookup'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roster_assignments');
    }
};
