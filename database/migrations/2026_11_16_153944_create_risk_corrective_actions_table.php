<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->onDelete('cascade');
            
            // Action Details
            $table->string('action_type')->comment('CORRECTIVE or PREVENTIVE');
            $table->text('action_description');
            $table->enum('priority', ['CRITICAL', 'HIGH', 'MEDIUM', 'LOW'])->default('MEDIUM');
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'COMPLETED', 'OVERDUE', 'CANCELLED'])->default('PENDING');
            
            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            
            // Verification
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            
            // Progress Tracking
            $table->integer('completion_percentage')->default(0);
            $table->text('progress_notes')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index(['risk_assessment_id', 'status']);
            $table->index(['assigned_to', 'due_date']);
            $table->index(['status', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_corrective_actions');
    }
};
