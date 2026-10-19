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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->text('user_message');
            $table->text('bot_response');
            $table->string('message_type')->default('rule'); // 'rule' or 'ai'
            $table->integer('response_time_ms')->nullable();
            $table->boolean('was_helpful')->nullable();
            $table->timestamps();
            $table->index('session_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
