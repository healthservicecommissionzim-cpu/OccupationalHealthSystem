<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            if (!Schema::hasColumn('incidents', 'service_connection_notes')) {
                $table->text('service_connection_notes')->nullable()->after('executive_recommendation_submitted_by_user_id')->comment('HR verification notes for service connection - cross-referencing supervisor report, witness statements, incident description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            if (Schema::hasColumn('incidents', 'service_connection_notes')) {
                $table->dropColumn('service_connection_notes');
            }
        });
    }
};
