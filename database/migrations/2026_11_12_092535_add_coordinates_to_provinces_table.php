<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('name');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });

        // Update existing provinces with coordinates (Zimbabwe province capitals)
        DB::table('provinces')->where('name', 'Harare')->update([
            'latitude' => -17.8252,
            'longitude' => 31.0335
        ]);

        DB::table('provinces')->where('name', 'Bulawayo')->update([
            'latitude' => -20.1324,
            'longitude' => 28.5829
        ]);

        DB::table('provinces')->where('name', 'Manicaland')->update([
            'latitude' => -18.9667,
            'longitude' => 32.6667
        ]);

        DB::table('provinces')->where('name', 'Mashonaland Central')->update([
            'latitude' => -16.1667,
            'longitude' => 31.1833
        ]);

        DB::table('provinces')->where('name', 'Mashonaland East')->update([
            'latitude' => -18.2333,
            'longitude' => 31.5667
        ]);

        DB::table('provinces')->where('name', 'Mashonaland West')->update([
            'latitude' => -17.6197,
            'longitude' => 29.8077
        ]);

        DB::table('provinces')->where('name', 'Masvingo')->update([
            'latitude' => -20.0638,
            'longitude' => 30.8294
        ]);

        DB::table('provinces')->where('name', 'Matabeleland North')->update([
            'latitude' => -19.4481,
            'longitude' => 27.8652
        ]);

        DB::table('provinces')->where('name', 'Matabeleland South')->update([
            'latitude' => -21.0593,
            'longitude' => 29.1402
        ]);

        DB::table('provinces')->where('name', 'Midlands')->update([
            'latitude' => -19.4500,
            'longitude' => 29.8167
        ]);
    }

    public function down(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
