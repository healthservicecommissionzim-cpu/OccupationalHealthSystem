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
        // Add performance indexes to incidents table - only most critical ones
        Schema::table('incidents', function (Blueprint $table) {
            // Status index - most frequently queried
            if (!Schema::hasIndex('incidents', ['status'])) {
                $table->index('status', 'idx_incidents_status');
            }
            
            // Composite index for status + created_at (dashboard date filters)
            if (!Schema::hasIndex('incidents', ['status', 'created_at'])) {
                $table->index(['status', 'created_at'], 'idx_incidents_status_created');
            }
            
            // HR user routing index
            if (!Schema::hasIndex('incidents', ['routed_to_hr_user_id', 'status'])) {
                $table->index(['routed_to_hr_user_id', 'status'], 'idx_incidents_hr_user_status');
            }
        });
        
        // Add performance indexes to users table
        Schema::table('users', function (Blueprint $table) {
            // Login query optimization: email + status
            if (!Schema::hasIndex('users', ['email', 'status'])) {
                $table->index(['email', 'status'], 'idx_users_email_status');
            }
            
            // User listing filter by status
            if (!Schema::hasIndex('users', ['status'])) {
                $table->index('status', 'idx_users_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from incidents table
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropIndex('idx_incidents_status');
            $table->dropIndex('idx_incidents_status_created');
            $table->dropIndex('idx_incidents_hr_user_status');
        });
        
        // Drop indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email_status');
            $table->dropIndex('idx_users_status');
        });
    }
};
