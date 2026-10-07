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
        Schema::create('monitoring_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('author_id');
            $table->longText('content');
            $table->timestamp('created_at')->nullable();
            
            // Foreign keys
            $table->foreign('case_id')
                ->references('id')
                ->on('incidents')
                ->onDelete('cascade');
            
            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
            
            // Indices for queries
            $table->index('case_id');
            $table->index('author_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_notes');
    }
};
