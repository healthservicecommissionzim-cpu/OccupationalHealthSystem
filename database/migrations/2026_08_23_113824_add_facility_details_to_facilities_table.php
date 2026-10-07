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
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('ownership')->nullable()->after('facility_type_id');
            $table->string('location')->nullable()->after('ownership');
            $table->string('level')->nullable()->after('location');
            $table->boolean('is_district_hospital')->default(false)->after('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['ownership', 'location', 'level', 'is_district_hospital']);
        });
    }
};
