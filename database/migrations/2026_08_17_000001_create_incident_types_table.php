<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert common health facility incident types
        DB::table('incident_types')->insert([
            ['name' => 'Slip, Trip or Fall', 'description' => 'Incidents involving slipping, tripping, or falling', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Needle Stick Injury', 'description' => 'Accidental puncture by needles or sharp instruments', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sharps Injury', 'description' => 'Injury from scalpels, broken glass, or other sharp objects', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Back Injury/Strain', 'description' => 'Injury from lifting, moving patients, or poor ergonomics', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chemical Exposure', 'description' => 'Exposure to cleaning agents, disinfectants, or hazardous chemicals', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biological Exposure', 'description' => 'Exposure to blood, bodily fluids, or infectious materials', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Violence/Assault', 'description' => 'Physical or verbal assault by patients, visitors, or staff', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Burn Injury', 'description' => 'Burns from hot surfaces, steam, or chemicals', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electric Shock', 'description' => 'Electrical injuries from equipment or wiring', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Struck by Object', 'description' => 'Hit by falling or moving objects', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cut/Laceration', 'description' => 'Cuts from equipment, tools, or other objects', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Motor Vehicle Accident', 'description' => 'Accidents involving vehicles during work duties', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Respiratory Exposure', 'description' => 'Inhalation of dust, aerosols, or harmful substances', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Radiation Exposure', 'description' => 'Exposure to X-rays or radioactive materials', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Equipment Malfunction', 'description' => 'Injury caused by faulty or malfunctioning equipment', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Overexertion', 'description' => 'Strain from excessive physical effort', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Caught in/between Objects', 'description' => 'Body part caught in machinery or between objects', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Eye Injury', 'description' => 'Injury to eyes from splashes, particles, or trauma', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Heat Stress/Exhaustion', 'description' => 'Illness from excessive heat or humidity', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'description' => 'Other types of incidents not listed above', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_types');
    }
};
