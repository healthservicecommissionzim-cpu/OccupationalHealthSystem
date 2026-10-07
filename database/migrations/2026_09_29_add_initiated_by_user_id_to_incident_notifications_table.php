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
            // Task 7.3: Add initiated_by_user_id to track which supervisor initiated SMS send
            $table->foreignId('initiated_by_user_id')
                ->nullable()
                ->after('recipient_user_id')
                ->constrained('users')
                ->onDelete('set null')
                ->comment('User who initiated this notification (e.g., supervisor sending reminder)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_notifications', function (Blueprint $table) {
            $table->dropForeignKey(['initiated_by_user_id']);
            $table->dropColumn('initiated_by_user_id');
        });
    }
};
