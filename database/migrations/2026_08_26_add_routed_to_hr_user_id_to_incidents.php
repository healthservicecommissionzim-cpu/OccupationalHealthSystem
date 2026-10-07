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
        Schema::table('incidents', function (Blueprint $table) {
            if (!Schema::hasColumn('incidents', 'routed_to_hr_user_id')) {
                $table->unsignedBigInteger('routed_to_hr_user_id')
                    ->nullable()
                    ->after('escalated_to_hr_at')
                    ->comment('Specific HR user assigned to review this incident');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            if (Schema::hasColumn('incidents', 'routed_to_hr_user_id')) {
                $table->dropColumn('routed_to_hr_user_id');
            }
        });
    }
};
