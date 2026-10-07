<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\Province;
use Illuminate\Database\Seeder;

class HealthFacilitiesSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('health_facilities.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found at: {$csvFile}");
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Skip header row

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 4) {
                continue; // Skip incomplete rows
            }

            $provinceName = trim($row[0]);
            $districtName = trim($row[1]);
            $facilityName = trim($row[2]);
            $typeName = trim($row[3]);

            // Create or find province
            $province = Province::firstOrCreate(
                ['name' => $provinceName],
                ['name' => $provinceName]
            );

            // Create or find district
            $district = District::firstOrCreate(
                ['province_id' => $province->id, 'name' => $districtName],
                ['province_id' => $province->id, 'name' => $districtName]
            );

            // Create or find facility type
            $facilityType = FacilityType::firstOrCreate(
                ['name' => $typeName],
                ['name' => $typeName]
            );

            // Create or find facility
            Facility::firstOrCreate(
                ['district_id' => $district->id, 'name' => $facilityName],
                [
                    'province_id' => $province->id,
                    'district_id' => $district->id,
                    'facility_type_id' => $facilityType->id,
                    'name' => $facilityName,
                ]
            );
        }

        fclose($file);
        $this->command->info('Health facilities seeded successfully!');
    }
}
