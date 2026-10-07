<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert the Conditions Of Service department if it doesn't exist
        DB::table('departments')->insertOrIgnore([
            'name' => 'Conditions Of Service',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the Conditions Of Service department
        DB::table('departments')->where('name', 'Conditions Of Service')->delete();
    }
};
