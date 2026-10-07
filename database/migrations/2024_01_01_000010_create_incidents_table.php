<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('first_name');
            $table->string('surname');
            $table->foreignId('designation_id')->constrained('designations');
            $table->foreignId('department_id')->constrained('departments');
            $table->string('id_number');
            $table->string('ec_number');
            $table->date('date_of_birth');
            $table->string('marital_status');
            $table->text('home_address');
            $table->string('phone_number');
            $table->string('email');
            $table->date('incident_date');
            $table->time('incident_time');
            $table->text('incident_description');
            $table->foreignId('facility_type_id')->constrained('facility_types');
            $table->foreignId('province_id')->constrained('provinces');
            $table->foreignId('district_id')->constrained('districts');
            $table->foreignId('facility_id')->constrained('facilities');
            $table->foreignId('supervisor_designation_id')->constrained('designations');
            $table->string('supervisor_name');
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
