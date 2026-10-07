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
        Schema::table('incident_notifications', function (Blueprint $table) {
            // Add witness token relationship (nullable for backwards compatibility)
            $table->foreignId('witness_token_id')->nullable()->after('incident_id')->constrained('incident_witness_tokens')->onDelete('cascade');
            
            // Add recipient phone for SMS notifications (nullable for non-SMS notifications)
            $table->string('recipient_phone')->nullable()->after('recipient_user_id');
            
            // Add message content field for SMS history
            $table->text('message_content')->nullable()->after('notification_channel');
            
            // Add error_message as alias (failure_reason already exists, keep both for compatibility)
            $table->text('error_message')->nullable()->after('read_at');
            
            // Add indexes for witness SMS queries
            $table->index('witness_token_id', 'idx_notifications_witness_token');
            $table->index('incident_id', 'idx_notifications_incident');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_notifications', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex('idx_notifications_witness_token');
            $table->dropIndex('idx_notifications_incident');
            
            // Drop new columns
            $table->dropColumn(['witness_token_id', 'recipient_phone', 'message_content', 'error_message']);
        });
    }
};
