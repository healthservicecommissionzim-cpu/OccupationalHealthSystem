<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Province;
use App\Models\District;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key constraints temporarily
        Schema::disableForeignKeyConstraints();

        // Clear existing districts
        District::query()->delete();

        // Define all Zimbabwe provinces and their districts
        $data = [
            'Mashonaland Central' => [
                'Bindura',
                'Centenary',
                'Guruve',
                'Mazowe',
                'Mbire',
                'Mount Darwin',
                'Rushinga',
                'Shamva',
            ],
            'Mashonaland East' => [
                'Chikomba',
                'Goromonzi',
                'Hwedza',
                'Marondera',
                'Mudzi',
                'Murewa',
                'Mutoko',
                'Seke',
                'Ump',
            ],
            'Mashonaland West' => [
                'Chegutu',
                'Hurungwe',
                'Kariba',
                'Makonde',
                'Mhondoro',
                'Sanyati',
                'Zvimba',
            ],
            'Manicaland' => [
                'Buhera',
                'Chimanimani',
                'Chipinge',
                'Makoni',
                'Mutare',
                'Mutasa',
                'Nyanga',
            ],
            'Masvingo' => [
                'Bikita',
                'Chiredzi',
                'Chivi',
                'Gutu',
                'Masvingo',
                'Mwenezi',
                'Zaka',
            ],
            'Matabeleland South' => [
                'Beitbridge',
                'Bulilima',
                'Gwanda',
                'Insiza',
                'Mangwe',
                'Matobo',
                'Umzingwane',
            ],
            'Matabeleland North' => [
                'Binga',
                'Bubi',
                'Hwange',
                'Lupane',
                'Nkayi',
                'Tsholotsho',
                'Umguza',
            ],
            'Midlands' => [
                'Chirumhanzu',
                'Gokwe North',
                'Gokwe South',
                'Gweru',
                'Kwekwe',
                'Mberengwa',
                'Shurugwi',
                'Zvishavane',
            ],
            'Harare' => [
                'Harare',
                'Chitungwiza',
            ],
            'Bulawayo' => [
                'Bulawayo',
            ],
        ];

        // Insert provinces and districts
        foreach ($data as $provinceName => $districts) {
            $province = Province::firstOrCreate(
                ['name' => $provinceName],
                ['name' => $provinceName]
            );

            foreach ($districts as $districtName) {
                District::create([
                    'province_id' => $province->id,
                    'name' => $districtName,
                ]);
            }
        }

        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Clear districts on rollback
        District::query()->delete();

        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }
};
