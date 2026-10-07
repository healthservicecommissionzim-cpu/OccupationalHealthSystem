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
            // RTW tracking fields
            $table->date('rtw_start_date')->nullable()->after('total_injury_leave_days')->comment('Date modified duties began');
            $table->date('rtw_end_date')->nullable()->after('rtw_start_date')->comment('Expected date of return to full duties');
            $table->longText('rtw_medical_restrictions')->nullable()->after('rtw_end_date')->comment('JSON: Medical restrictions from doctor');
            $table->longText('rtw_modified_duties')->nullable()->after('rtw_medical_restrictions')->comment('JSON: Specific tasks assigned during RTW');
            $table->longText('rtw_schedule')->nullable()->after('rtw_modified_duties')->comment('JSON: Graduated work schedule progression');
            $table->text('rtw_accommodations')->nullable()->after('rtw_schedule')->comment('Physical/equipment accommodations needed');
            $table->date('rtw_last_review_date')->nullable()->after('rtw_accommodations')->comment('Last RTW progress review date');
            $table->date('rtw_next_review_date')->nullable()->after('rtw_last_review_date')->comment('Next scheduled RTW review date');
            $table->enum('rtw_status', ['not_started', 'active', 'on_track', 'requires_intervention', 'completed', 'terminated'])->default('not_started')->after('rtw_next_review_date')->comment('Current RTW status');
            $table->text('rtw_review_notes')->nullable()->after('rtw_status')->comment('Notes from RTW reviews');
            $table->boolean('rtw_doctor_clearance_received')->default(false)->after('rtw_review_notes')->comment('Doctor provided return-to-work clearance');
            $table->boolean('rtw_plan_signed_off')->default(false)->after('rtw_doctor_clearance_received')->comment('RTW plan signed by all stakeholders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn([
                'rtw_start_date',
                'rtw_end_date',
                'rtw_medical_restrictions',
                'rtw_modified_duties',
                'rtw_schedule',
                'rtw_accommodations',
                'rtw_last_review_date',
                'rtw_next_review_date',
                'rtw_status',
                'rtw_review_notes',
                'rtw_doctor_clearance_received',
                'rtw_plan_signed_off',
            ]);
        });
    }
};
