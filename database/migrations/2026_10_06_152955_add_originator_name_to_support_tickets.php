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
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->string('originator_name')->nullable()->after('originator_user_id');
            $table->string('originator_email')->nullable()->after('originator_name');
            $table->string('originator_phone')->nullable()->after('originator_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn(['originator_name', 'originator_email', 'originator_phone']);
        });
    }
};
