<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->text('hod_report')->nullable()->after('supervisor_witness_data');
            $table->timestamp('hod_report_submitted_at')->nullable()->after('hod_report');
            $table->foreignId('hod_report_submitted_by')->nullable()->constrained('users')->onDelete('set null')->after('hod_report_submitted_at');
            $table->boolean('hod_acknowledged_witness_statements')->default(false)->after('hod_report_submitted_by');
        });
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn(['hod_report', 'hod_report_submitted_at', 'hod_report_submitted_by', 'hod_acknowledged_witness_statements']);
        });
    }
};
