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
        Schema::table('incident_attachments', function (Blueprint $table) {
            // Add witness_token_id for linking attachments to witness statements
            $table->foreignId('witness_token_id')->nullable()->after('incident_id')->constrained('incident_witness_tokens')->onDelete('cascade');
            
            // Add index for efficient querying
            $table->index('witness_token_id', 'idx_attachments_witness_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_attachments', function (Blueprint $table) {
            $table->dropIndex('idx_attachments_witness_token');
            $table->dropColumn('witness_token_id');
        });
    }
};
