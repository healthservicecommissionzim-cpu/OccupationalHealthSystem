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
        // The incident_notifications table already has most columns
        // Just ensure read_at timestamp exists for marking as read
        if (!Schema::hasColumn('incident_notifications', 'read_at')) {
            Schema::table('incident_notifications', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Minimal rollback - table already existed
    }
};
