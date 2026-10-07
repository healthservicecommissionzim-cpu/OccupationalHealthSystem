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
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets');
            $table->foreignId('sender_user_id')->constrained('users');
            $table->longText('message');
            $table->string('sender_role', 50)->nullable();
            $table->enum('status', ['PENDING', 'SENT'])->default('SENT');
            $table->timestamps();

            // Indexes for query performance
            $table->index('support_ticket_id');
            $table->index('sender_user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
    }
};
