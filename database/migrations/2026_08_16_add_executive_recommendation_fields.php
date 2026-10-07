<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Executive audit verification
            $table->boolean('executive_audit_verified')->default(false)->after('routed_to_cmo_user_id')->comment('Executive has verified case file audit');
            $table->timestamp('executive_audit_verified_at')->nullable()->after('executive_audit_verified')->comment('When executive verified the audit');
            $table->unsignedBigInteger('executive_audit_verified_by_user_id')->nullable()->after('executive_audit_verified_at')->comment('Which executive verified');
            
            // Executive recommendation endorsement letter
            $table->longText('executive_recommendation_text')->nullable()->after('executive_audit_verified_by_user_id')->comment('Executive recommendation & endorsement letter');
            $table->timestamp('executive_recommendation_submitted_at')->nullable()->after('executive_recommendation_text')->comment('When recommendation was submitted');
            $table->unsignedBigInteger('executive_recommendation_submitted_by_user_id')->nullable()->after('executive_recommendation_submitted_at')->comment('Which executive submitted recommendation');
            
            // Routing to next level after executive review
            $table->string('routed_to_pmd_status')->nullable()->after('executive_recommendation_submitted_by_user_id')->comment('Status for PMD routing: PENDING_PMD_REVIEW, ESCALATED_TO_PMD, ESCALATED_TO_MINISTRY_HQ');
            $table->unsignedBigInteger('routed_to_pmd_user_id')->nullable()->after('routed_to_pmd_status')->comment('PMD assigned for oversight');
        });
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn([
                'executive_audit_verified',
                'executive_audit_verified_at',
                'executive_audit_verified_by_user_id',
                'executive_recommendation_text',
                'executive_recommendation_submitted_at',
                'executive_recommendation_submitted_by_user_id',
                'routed_to_pmd_status',
                'routed_to_pmd_user_id',
            ]);
        });
    }
};
