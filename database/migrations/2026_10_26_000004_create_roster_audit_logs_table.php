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
        Schema::create('roster_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type', 255);
            $table->unsignedBigInteger('auditable_id');
            $table->enum('action_type', ['created', 'updated', 'deleted', 'deactivated', 'cancelled']);
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Indexes for querying audit logs
            $table->index(['auditable_type', 'auditable_id'], 'idx_roster_audit_logs_auditable');
            $table->index('user_id', 'idx_roster_audit_logs_user_id');
            $table->index('action_type', 'idx_roster_audit_logs_action_type');
            $table->index('created_at', 'idx_roster_audit_logs_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roster_audit_logs');
    }
};
