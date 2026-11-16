<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_assessment_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->onDelete('cascade');
            
            // Event Details
            $table->string('event_type')->comment('CREATED, SCORE_CHANGED, INCIDENT_TRIGGERED, REVIEWED, APPROVED, STATUS_CHANGED');
            $table->text('event_description');
            
            // Change Tracking
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // Incident Trigger
            $table->foreignId('triggered_by_incident_id')->nullable()->constrained('incidents')->onDelete('set null');
            
            // User & Timestamp
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['risk_assessment_id', 'created_at']);
            $table->index(['event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_assessment_audit_logs');
    }
};
