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
            // Only add HOD Report fields if they don't exist
            if (!Schema::hasColumn('incidents', 'hod_report')) {
                $table->longText('hod_report')->nullable()->after('status');
            }
            if (!Schema::hasColumn('incidents', 'hod_report_submitted_at')) {
                $table->timestamp('hod_report_submitted_at')->nullable()->after('hod_report');
            }
            if (!Schema::hasColumn('incidents', 'hod_report_submitted_by')) {
                $table->unsignedBigInteger('hod_report_submitted_by')->nullable()->after('hod_report_submitted_at');
            }
            if (!Schema::hasColumn('incidents', 'hod_acknowledged_witness_statements')) {
                $table->boolean('hod_acknowledged_witness_statements')->default(false)->after('hod_report_submitted_by');
            }
            
            // Only add HR Routing fields if they don't exist
            if (!Schema::hasColumn('incidents', 'hr_route_type')) {
                $table->string('hr_route_type')->nullable()->comment('DISTRICT_HR_REVIEW | HOSPITAL_HR_REVIEW | CENTRAL_HR_REVIEW')->after('hod_acknowledged_witness_statements');
            }
            if (!Schema::hasColumn('incidents', 'hr_assigned_group_id')) {
                $table->unsignedBigInteger('hr_assigned_group_id')->nullable()->comment('District HR / Hospital HR / Central HR group ID')->after('hr_route_type');
            }
            if (!Schema::hasColumn('incidents', 'escalated_to_hr_at')) {
                $table->timestamp('escalated_to_hr_at')->nullable()->after('hr_assigned_group_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn([
                'hod_report',
                'hod_report_submitted_at',
                'hod_report_submitted_by',
                'hod_acknowledged_witness_statements',
                'hr_route_type',
                'hr_assigned_group_id',
                'escalated_to_hr_at',
            ]);
        });
    }
};
