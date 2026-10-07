<?php

namespace App\Console\Commands;

use App\Models\District;
use App\Models\Province;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshDistrictsFromCSV extends Command
{
    protected $signature = 'districts:refresh {--force : Skip confirmation}';
    protected $description = 'Refresh all districts from Zimbabwe data and CSV file';

    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('This will delete all existing districts and reseed them.');
            
            if (!$this->confirm('Do you want to continue?')) {
                $this->info('Cancelled.');
                return 0;
            }
        }

        $this->info('Step 1: Clearing existing districts...');
        DB::transaction(function () {
            District::query()->delete();
        });
        $this->info('✓ Districts cleared');

        // Define all Zimbabwe provinces and their districts
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

        $this->info('Step 2: Creating provinces and districts...');
        $districtCount = 0;

        DB::transaction(function () use ($data, &$districtCount) {
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
                    $districtCount++;
                }
            }
        });

        $this->info("✓ Created {$districtCount} districts");

        // Now seed facilities from CSV if file exists
        $csvFile = database_path('List of facilities by category Zimbabwe  current.csv');
        if (file_exists($csvFile)) {
            $this->info('Step 3: Seeding facilities from CSV...');
            $this->call('db:seed', ['--class' => 'UpdatedHealthFacilitiesSeeder']);
        } else {
            $this->warn("CSV file not found at {$csvFile}. Please ensure your CSV file is in place.");
        }

        $this->info('✓ Districts refresh complete!');
        return 0;
    }
}
