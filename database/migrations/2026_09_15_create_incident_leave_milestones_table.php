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
        Schema::create('incident_leave_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id');
            $table->enum('milestone_type', ['90_DAY', '180_DAY']);
            $table->integer('days_elapsed_at_flag');
            $table->string('flag_reason', 255);
            $table->boolean('applied_by_system')->default(true);
            $table->unsignedBigInteger('applied_by_user_id')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('cascade');
            $table->foreign('applied_by_user_id')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('milestone_type');
            $table->index('applied_at');
            $table->index('incident_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_leave_milestones');
    }
};
