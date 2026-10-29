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
        Schema::create('staff_rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->enum('staff_type', ['permanent', 'locum', 'student', 'rotational']);
            $table->foreignId('primary_facility_id')->constrained('facilities')->onDelete('restrict');
            $table->boolean('active')->default(true);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            // Indexes for performance
            $table->index('user_id', 'idx_staff_rosters_user_id');
            $table->index('primary_facility_id', 'idx_staff_rosters_primary_facility_id');
            $table->index('staff_type', 'idx_staff_rosters_staff_type');
            $table->index('active', 'idx_staff_rosters_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_rosters');
    }
};
