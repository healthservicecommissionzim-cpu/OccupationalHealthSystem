<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            
            // Location & Context
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('specific_zone')->nullable()->comment('Specific area/zone within facility');
            $table->string('task_type')->nullable()->comment('Type of task/activity being assessed');
            $table->string('equipment_involved')->nullable()->comment('Equipment/machinery involved');
            
            // Hazard Description
            $table->text('hazard_description')->comment('Description of the identified hazard');
            $table->string('hazard_category')->nullable()->comment('Category: Physical, Chemical, Biological, Ergonomic, Psychosocial');
            
            // Inherent Risk (Before Controls)
            $table->integer('inherent_likelihood')->comment('1-5: Likelihood before controls')->default(3);
            $table->integer('inherent_severity')->comment('1-5: Severity before controls')->default(3);
            $table->integer('inherent_risk_score')->virtualAs('inherent_likelihood * inherent_severity');
            
            // Control Measures (Hierarchy of Controls)
            $table->json('control_measures')->nullable()->comment('Array of control measures categorized by hierarchy');
            $table->text('elimination_controls')->nullable();
            $table->text('substitution_controls')->nullable();
            $table->text('engineering_controls')->nullable();
            $table->text('administrative_controls')->nullable();
            $table->text('ppe_controls')->nullable();
            
            // Residual Risk (After Controls)
            $table->integer('residual_likelihood')->comment('1-5: Likelihood after controls')->default(2);
            $table->integer('residual_severity')->comment('1-5: Severity after controls')->default(2);
            $table->integer('residual_risk_score')->virtualAs('residual_likelihood * residual_severity');
            
            // Predictive Analytics Fields
            $table->integer('predicted_likelihood')->nullable()->comment('AI-calculated likelihood based on historical data');
            $table->integer('predicted_severity')->nullable()->comment('AI-calculated severity based on historical data');
            $table->integer('predicted_risk_score')->virtualAs('predicted_likelihood * predicted_severity');
            $table->boolean('escalation_warning')->default(false)->comment('Flag for trending upward risk');
            $table->text('predictive_insights')->nullable()->comment('AI-generated insights about risk patterns');
            
            // Incident Linkage
            $table->integer('linked_incidents_30d')->default(0)->comment('Incidents at this location in last 30 days');
            $table->integer('linked_incidents_90d')->default(0)->comment('Incidents at this location in last 90 days');
            $table->integer('linked_incidents_365d')->default(0)->comment('Incidents at this location in last 365 days');
            $table->integer('linked_dod_total')->default(0)->comment('Total DODs at this location (all time)');
            $table->text('probable_causes')->nullable()->comment('Recurring root causes from historical incidents');
            
            // Status & Audit
            $table->enum('status', ['ACTIVE', 'NEEDS_REVIEW', 'EXPIRED', 'ARCHIVED'])->default('ACTIVE');
            $table->date('last_reviewed_date')->nullable();
            $table->date('next_review_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['facility_id', 'status']);
            $table->index(['residual_risk_score']);
            $table->index(['predicted_risk_score']);
            $table->index(['next_review_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
