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
            // Conditional document flags
            $table->boolean('is_workplace_fatality')->default(false)->after('status')->comment('Workplace fatality flag - triggers mandatory death documents');
            $table->boolean('is_road_traffic_accident')->default(false)->after('is_workplace_fatality')->comment('Road traffic accident flag - triggers mandatory accident documents');
            
            // HR review tracking
            $table->timestamp('hr_dossier_acknowledged_at')->nullable()->after('escalated_to_hr_at')->comment('When HR officer acknowledged case dossier');
            $table->unsignedBigInteger('hr_dossier_acknowledged_by')->nullable()->after('hr_dossier_acknowledged_at')->comment('User ID of HR officer who acknowledged');
            $table->timestamp('hr_reviewed_at')->nullable()->after('hr_dossier_acknowledged_by')->comment('When HR completed review');
            $table->unsignedBigInteger('hr_reviewed_by')->nullable()->after('hr_reviewed_at')->comment('User ID of HR officer who reviewed');
            $table->string('hr_compliance_status')->nullable()->after('hr_reviewed_by')->comment('APPROVED, REJECTED, PENDING');
            $table->json('hr_rejection_reasons')->nullable()->after('hr_compliance_status')->comment('Reasons for document rejection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn([
                'is_workplace_fatality',
                'is_road_traffic_accident',
                'hr_dossier_acknowledged_at',
                'hr_dossier_acknowledged_by',
                'hr_reviewed_at',
                'hr_reviewed_by',
                'hr_compliance_status',
                'hr_rejection_reasons',
            ]);
        });
    }
};
