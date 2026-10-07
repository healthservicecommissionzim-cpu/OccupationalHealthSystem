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
        Schema::table('incident_witness_tokens', function (Blueprint $table) {
            $table->timestamp('witness_last_reminder_sent_at')->nullable()->after('statement_submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_witness_tokens', function (Blueprint $table) {
            $table->dropColumn('witness_last_reminder_sent_at');
        });
    }
};
