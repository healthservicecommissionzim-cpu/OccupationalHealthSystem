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
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->longText('content');
            $table->unsignedBigInteger('author_id');
            $table->enum('target_audience', ['supervisors', 'pmds', 'doctors', 'all_staff'])->default('all_staff');
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->nullable();
            
            // Foreign keys
            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
            
            // Indices for queries
            $table->index('author_id');
            $table->index('target_audience');
            $table->index('published_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
