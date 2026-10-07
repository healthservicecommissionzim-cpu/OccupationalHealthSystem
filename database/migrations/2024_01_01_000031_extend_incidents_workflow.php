<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new columns to incidents table
        Schema::table('incidents', function (Blueprint $table) {
            $table->string('status')->change()->comment('Incident workflow status: PENDING_SUPERVISOR_VERIFICATION, SUPERVISOR_VERIFIED, PENDING_MEDICAL_REVIEW, COMPLETED, REJECTED');
            $table->foreignId('assigned_supervisor_id')->nullable()->constrained('users')->onDelete('set null')->after('supervisor_designation_id');
            $table->timestamp('supervisor_verified_at')->nullable()->after('assigned_supervisor_id');
            $table->text('supervisor_witness_statement')->nullable()->after('supervisor_verified_at');
            $table->string('compliance_status')->default('NONCOMPLIANT')->comment('NONCOMPLIANT, PARTIAL_COMPLIANT, COMPLIANT')->after('supervisor_witness_statement');
        });

        // Create incident_attachments table (polymorphic file storage)
        Schema::create('incident_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade');
            $table->string('document_type')->comment('NSSA_FORM_WCA1, INITIAL_MEDICAL_TREATMENT, OTHER');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->string('upload_status')->default('PENDING')->comment('PENDING, VERIFIED, REJECTED');
            $table->text('upload_notes')->nullable();
            $table->foreignId('uploaded_by_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // Create incident_notifications table for tracking sent alerts
        Schema::create('incident_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade');
            $table->foreignId('recipient_user_id')->constrained('users')->onDelete('cascade');
            $table->string('notification_type')->comment('SUPERVISOR_ALERT, VERIFICATION_REMINDER, WORKFLOW_UPDATE');
            $table->string('notification_channel')->default('in_app')->comment('in_app, email, sms');
            $table->string('status')->default('SENT')->comment('SENT, DELIVERED, READ, FAILED');
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_notifications');
        Schema::dropIfExists('incident_attachments');
        
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeignIdFor(User::class, 'assigned_supervisor_id');
            $table->dropColumn(['assigned_supervisor_id', 'supervisor_verified_at', 'supervisor_witness_statement', 'compliance_status']);
        });
    }
};
