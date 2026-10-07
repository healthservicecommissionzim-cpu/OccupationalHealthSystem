<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Seeder;

class ZimbabweDistrictsSeeder extends Seeder
{
    public function run(): void
    {
        // Define all Zimbabwe provinces with their districts
        $data = [
            'Mashonaland Central' => [
                'Chegutu',
                'Hurungwe',
                'Kariba',
                'Makonde',
                'Mhondoro',
                'Sanyati',
                'Zvimba',
            ],
            'Mashonaland East' => [
                'Bindura',
                'Centenary',
                'Guruve',
                'Mazowe',
                'Mbirire',
                'Mount Darwin',
                'Rushinga',
                'Shamva',
            ],
            'Mashonaland West' => [
                'Binga',
                'Bubi',
                'Hwange',
                'Lupane',
                'Nkayi',
                'Tsholotsho',
                'Umguza',
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
                'Matopo',
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
            ],
            'Bulawayo' => [
                'Bulawayo',
            ],
        ];

        foreach ($data as $provinceName => $districts) {
            // Create or find province
            $province = Province::firstOrCreate(
                ['name' => $provinceName],
                ['name' => $provinceName]
            );

            // Create or find all districts for this province
            foreach ($districts as $districtName) {
                District::firstOrCreate(
                    ['province_id' => $province->id, 'name' => $districtName],
                    ['province_id' => $province->id, 'name' => $districtName]
                );
            }
        }

        $this->command->info('Zimbabwe districts seeded successfully!');
    }
}
