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
        Schema::create('ticket_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets');
            $table->foreignId('recipient_user_id')->constrained('users');
            $table->string('notification_type', 50);
            $table->string('notification_channel', 50);
            $table->longText('content');
            $table->enum('status', ['PENDING', 'SENT', 'FAILED'])->default('PENDING');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Indexes for query performance
            $table->index('support_ticket_id');
            $table->index('recipient_user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_notifications');
    }
};
