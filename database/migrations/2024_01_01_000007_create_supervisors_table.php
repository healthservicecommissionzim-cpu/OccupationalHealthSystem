<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('designation_title'); // e.g., "Consultant Emergency Physician"
            $table->string('role_type'); // e.g., "HOD", "Matron", "Sister-in-Charge", "Registrar"
            $table->integer('hierarchy_level'); // 1=HOD, 2=Deputy, 3=Staff, etc.
            $table->boolean('is_hod')->default(false); // Head of Department flag
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
