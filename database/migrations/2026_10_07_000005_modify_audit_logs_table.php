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
        // audit_logs table already has case_id and action columns
        // Just ensure indices exist for performance
        if (!Schema::hasColumn('audit_logs', 'case_id')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('case_id')->nullable();
                $table->foreign('case_id')
                    ->references('id')
                    ->on('incidents')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Minimal rollback - case_id already existed
    }
};
