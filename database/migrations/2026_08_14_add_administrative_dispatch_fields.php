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
            // Administrative Dispatch Fields
            $table->text('hr_compliance_notes')->nullable()->comment('HR officer administrative summary (min 50 chars)');
            $table->timestamp('hr_compliance_notes_saved_at')->nullable()->comment('When compliance notes were saved');
            
            // Physical Hand-off Verification
            $table->string('social_services_receiving_official')->nullable()->comment('Name of receiving official at Social Services');
            $table->timestamp('physical_handoff_timestamp')->nullable()->comment('When physical delivery was made to Social Services');
            $table->unsignedBigInteger('physical_handoff_by_user_id')->nullable()->comment('HR officer who physically delivered');
            
            // Signed Manifest Upload
            $table->string('signed_manifest_file_path')->nullable()->comment('Path to signed/stamped delivery manifest');
            $table->timestamp('signed_manifest_uploaded_at')->nullable()->comment('When signed manifest was uploaded');
            
            // Final Submission Status
            $table->timestamp('submitted_to_social_services_at')->nullable()->comment('When case was finalized and submitted');
            $table->string('submission_status')->default('PENDING')->comment('PENDING, SUBMITTED_TO_SOCIAL_SERVICES, APPROVED, REJECTED');
            
            // Routing to Executive Officers
            $table->unsignedBigInteger('routed_to_dmo_user_id')->nullable()->comment('DMO assigned for oversight');
            $table->unsignedBigInteger('routed_to_medical_superintendent_user_id')->nullable()->comment('Medical Superintendent assigned');
            $table->unsignedBigInteger('routed_to_cmo_user_id')->nullable()->comment('CMO assigned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn([
                'hr_compliance_notes',
                'hr_compliance_notes_saved_at',
                'social_services_receiving_official',
                'physical_handoff_timestamp',
                'physical_handoff_by_user_id',
                'signed_manifest_file_path',
                'signed_manifest_uploaded_at',
                'submitted_to_social_services_at',
                'submission_status',
                'routed_to_dmo_user_id',
                'routed_to_medical_superintendent_user_id',
                'routed_to_cmo_user_id',
            ]);
        });
    }
};
