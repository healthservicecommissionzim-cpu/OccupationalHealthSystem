<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_witness_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade');
            $table->string('witness_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('token')->unique();
            $table->text('statement')->nullable();
            $table->timestamp('token_sent_at')->nullable();
            $table->timestamp('statement_submitted_at')->nullable();
            $table->timestamp('token_expired_at')->nullable();
            $table->boolean('is_expired')->default(false);
            $table->timestamps();

            $table->index(['incident_id', 'is_expired']);
            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_witness_tokens');
    }
};
