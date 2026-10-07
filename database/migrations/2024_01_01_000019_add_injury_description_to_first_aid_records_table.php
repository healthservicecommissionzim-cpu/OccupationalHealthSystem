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
        Schema::table('first_aid_records', function (Blueprint $table) {
            $table->text('injury_description')->after('nurse_license_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('first_aid_records', function (Blueprint $table) {
            $table->dropColumn('injury_description');
        });
    }
};
