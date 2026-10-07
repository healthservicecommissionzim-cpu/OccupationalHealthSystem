<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // Event ID - Globally unique identifier
            $table->string('event_id')->unique()->nullable();
            
            // Correlation IDs
            $table->uuid('correlation_id')->nullable();
            $table->string('request_id')->unique()->nullable();
            
            // User & Session tracking
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('impersonated_by_user_id')->nullable();
            $table->string('impersonated_by_username')->nullable();
            
            // Action details
            $table->string('action')->nullable();
            $table->string('component')->nullable();
            $table->string('affected_entity')->nullable();
            
            // HTTP context
            $table->string('http_method')->default('GET');
            $table->unsignedSmallInteger('http_code')->default(200);
            $table->unsignedInteger('duration_ms')->default(0);
            
            // Status & Errors
            $table->enum('status', ['SUCCESS', 'FAILED'])->default('SUCCESS');
            $table->text('error_message')->nullable();
            
            // Resource information
            $table->string('resource_type')->nullable();
            $table->string('resource_path')->nullable();
            $table->string('auditable_type')->nullable();
            $table->string('auditable_id')->nullable();
            
            // Data change tracking
            $table->longText('old_values')->nullable();
            $table->longText('new_values')->nullable();
            $table->json('data_before')->nullable();
            $table->json('data_after')->nullable();
            $table->json('fields_modified')->nullable();
            $table->enum('change_magnitude', ['MINOR', 'MODERATE', 'SIGNIFICANT', 'CRITICAL'])->default('MODERATE');
            
            // Source/Location tracking
            $table->ipAddress('ip_address')->nullable();
            $table->string('ip_version')->default('IPv4');
            $table->string('hostname')->nullable();
            $table->string('geo_location')->nullable();
            $table->string('geo_country')->nullable();
            $table->string('geo_city')->nullable();
            $table->decimal('geo_latitude', 10, 8)->nullable();
            $table->decimal('geo_longitude', 11, 8)->nullable();
            $table->text('user_agent')->nullable();
            
            // Integrity & Security
            $table->string('integrity_hash')->nullable();
            $table->string('content_hash')->nullable();
            $table->string('hash_algorithm')->default('SHA256');
            $table->boolean('hash_verified')->default(false);
            $table->text('signature')->nullable();
            $table->dateTimeTz('signature_timestamp')->nullable();
            
            // Compliance & Classification
            $table->boolean('requires_notification')->default(false);
            $table->json('notification_recipients')->nullable();
            $table->enum('retention_policy', ['STANDARD_1_YEAR', 'EXTENDED_3_YEARS', 'CRITICAL_ACTION_7_YEARS', 'PERMANENT'])->default('STANDARD_1_YEAR');
            $table->enum('classification', ['ROUTINE', 'IMPORTANT', 'AUDIT_CRITICAL', 'SECURITY_SENSITIVE'])->default('ROUTINE');
            
            // Timestamps
            $table->dateTimeTz('timestamp_utc')->useCurrent();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('event_id');
            $table->index('request_id');
            $table->index('session_id');
            $table->index('timestamp_utc');
            $table->index('user_id');
            $table->index('classification');
            $table->index(['user_id', 'timestamp_utc']);
            $table->index(['action', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
