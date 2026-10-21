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
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('communication_type', [
                'REGISTRATION',
                'APPROVAL',
                'REJECTION',
                'INCIDENT_CONFIRMATION',
                'DOCUMENT_UPLOAD',
                'PASSWORD_RESET',
                'MILESTONE_ALERT',
                'HR_ESCALATION'
            ])->index();
            $table->enum('channel', ['email', 'sms', 'database'])->index();
            $table->string('recipient_email')->nullable()->index();
            $table->string('recipient_phone')->nullable();
            $table->unsignedBigInteger('recipient_user_id')->nullable()->index();
            $table->string('subject')->nullable();
            $table->text('message_preview')->nullable();
            $table->longText('full_content')->nullable();
            $table->enum('status', ['PENDING', 'SENT', 'FAILED'])->default('PENDING')->index();
            $table->string('error_message')->nullable();
            $table->string('related_entity_type')->nullable();
            $table->unsignedBigInteger('related_entity_id')->nullable();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();

            // Add foreign key
            $table->foreign('recipient_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_logs');
    }
};
