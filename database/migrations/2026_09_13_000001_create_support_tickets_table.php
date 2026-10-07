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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();
            $table->foreignId('originator_user_id')->constrained('users');
            $table->foreignId('routed_to_super_admin_id')->nullable()->constrained('users');
            $table->string('title', 255);
            $table->string('category', 50);
            $table->longText('description');
            $table->enum('status', ['OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED'])->default('OPEN');
            $table->string('created_by_role', 50)->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            // Indexes for query performance
            $table->index('originator_user_id');
            $table->index('routed_to_super_admin_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
