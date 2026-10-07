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
            // Injury leave tracking fields - mandatory for Stage 5 Document Verification
            $table->date('injury_leave_start_date')->nullable()->after('hr_compliance_notes_saved_at')->comment('Start date of injury-related sick leave');
            $table->date('injury_leave_end_date')->nullable()->after('injury_leave_start_date')->comment('End date of injury-related sick leave');
            $table->integer('total_injury_leave_days')->nullable()->after('injury_leave_end_date')->comment('Calculated total calendar days of injury leave');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn([
                'injury_leave_start_date',
                'injury_leave_end_date',
                'total_injury_leave_days',
            ]);
        });
    }
};
