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
        Schema::create('first_aid_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained()->onDelete('cascade');
            $table->string('nurse_name');
            $table->string('nurse_license_number');
            $table->text('first_aid_description');
            $table->timestamp('time_administered');
            $table->string('location');
            $table->enum('severity_level', ['minor', 'moderate', 'severe'])->default('minor');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending')->comment('pending, approved, rejected');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('first_aid_records');
    }
};
