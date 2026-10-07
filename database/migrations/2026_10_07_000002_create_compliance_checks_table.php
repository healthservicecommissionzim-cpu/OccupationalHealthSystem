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
        Schema::create('compliance_checks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('evaluated_by_id');
            $table->json('checklist_items');
            $table->enum('overall_status', ['COMPLIANT', 'NON_COMPLIANT'])->default('NON_COMPLIANT');
            $table->timestamp('created_at')->nullable();
            
            // Foreign keys
            $table->foreign('case_id')
                ->references('id')
                ->on('incidents')
                ->onDelete('cascade');
            
            $table->foreign('evaluated_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
            
            // Indices for queries
            $table->index('case_id');
            $table->index('evaluated_by_id');
            $table->index('overall_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_checks');
    }
};
