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
        Schema::create('iod_document_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->unique()->constrained('incidents')->onDelete('cascade');

            // IOD Required Documents Status
            $table->boolean('doctors_report_uploaded')->default(false);
            $table->boolean('injury_report_uploaded')->default(false);
            $table->boolean('sick_leave_uploaded')->default(false);
            $table->boolean('witness_statements_2plus_uploaded')->default(false)->comment('At least 2 required');
            $table->boolean('job_duties_uploaded')->default(false);
            $table->boolean('national_id_uploaded')->default(false);
            $table->boolean('bank_statements_uploaded')->default(false);
            $table->boolean('treatment_records_uploaded')->default(false);
            $table->boolean('banking_details_uploaded')->default(false);
            $table->boolean('contact_details_uploaded')->default(false);
            $table->boolean('late_submission_reason_uploaded')->nullable()->comment('Only if applicable');

            // Verification Status
            $table->boolean('doctors_report_verified')->default(false);
            $table->boolean('injury_report_verified')->default(false);
            $table->boolean('sick_leave_verified')->default(false);
            $table->boolean('witness_statements_verified')->default(false);
            $table->boolean('job_duties_verified')->default(false);
            $table->boolean('national_id_verified')->default(false);
            $table->boolean('bank_statements_verified')->default(false);
            $table->boolean('treatment_records_verified')->default(false);
            $table->boolean('banking_details_verified')->default(false);

            // Overall Status
            $table->enum('checklist_status', ['INCOMPLETE', 'PENDING_VERIFICATION', 'VERIFIED', 'REJECTED'])->default('INCOMPLETE');
            $table->integer('documents_uploaded_count')->default(0);
            $table->integer('documents_verified_count')->default(0);
            $table->integer('total_required_documents')->default(9);
            $table->integer('completion_percentage')->default(0)->comment('Calculated: uploaded_count / total_required * 100');

            // Notification Tracking
            $table->timestamp('worker_notified_incomplete_at')->nullable();
            $table->timestamp('worker_reminder_sent_at')->nullable();
            $table->boolean('all_documents_submitted')->default(false);
            $table->timestamp('all_submitted_at')->nullable();

            // HR Review
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verification_completed_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->text('rejection_reason')->nullable();

            // Audit
            $table->timestamps();

            // Indexes
            $table->index('checklist_status');
            $table->index('all_documents_submitted');
            $table->index('completion_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iod_document_checklists');
    }
};
