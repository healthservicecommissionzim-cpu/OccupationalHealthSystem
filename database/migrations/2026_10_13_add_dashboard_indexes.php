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
        // Index for user status queries
        Schema::table('users', function (Blueprint $table) {
            $table->index('status');
            $table->index('organization_id');
            $table->index(['status', 'organization_id']);
        });

        // Index for incident status queries
        Schema::table('incidents', function (Blueprint $table) {
            $table->index('status');
            $table->index('province_id');
            $table->index('district_id');
            $table->index(['status', 'created_at']);
            $table->index('created_at');
        });

        // Index for rejection logs
        Schema::table('rejection_logs', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['organization_id']);
            $table->dropIndex(['status', 'organization_id']);
        });

        Schema::table('incidents', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['province_id']);
            $table->dropIndex(['district_id']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('rejection_logs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id']);
        });
    }
};
