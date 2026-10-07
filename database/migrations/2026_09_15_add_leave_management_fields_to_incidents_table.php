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
            // Leave management fields
            $table->date('sick_leave_start_date')->nullable()->after('routed_to_hr_user_id');
            $table->boolean('medical_certificate_provided')->default(false)->after('sick_leave_start_date');
            $table->date('expected_recovery_date')->nullable()->after('medical_certificate_provided');
            $table->enum('leave_payment_stage', ['FULL_PAY', 'HALF_PAY'])->nullable()->after('expected_recovery_date');
            $table->string('leave_status', 50)->default('ACTIVE')->after('leave_payment_stage');
            $table->timestamp('leave_information_saved_at')->nullable()->after('leave_status');
            $table->unsignedBigInteger('saved_by_user_id')->nullable()->after('leave_information_saved_at');
            
            // Add indexes for dashboard queries
            $table->index('sick_leave_start_date');
            $table->index('leave_payment_stage');
            $table->index('leave_status');
            
            // Add foreign key
            $table->foreign('saved_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['saved_by_user_id']);
            
            // Drop indexes
            $table->dropIndex(['sick_leave_start_date']);
            $table->dropIndex(['leave_payment_stage']);
            $table->dropIndex(['leave_status']);
            
            // Drop columns
            $table->dropColumn([
                'sick_leave_start_date',
                'medical_certificate_provided',
                'expected_recovery_date',
                'leave_payment_stage',
                'leave_status',
                'leave_information_saved_at',
                'saved_by_user_id',
            ]);
        });
    }
};
