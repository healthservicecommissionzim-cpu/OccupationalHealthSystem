<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\Province;
use Illuminate\Database\Seeder;

class UpdatedHealthFacilitiesSeeder extends Seeder
{
    /**
     * Seed health facilities from CSV with format:
     * Province, Districts, Facilities, Type of Facility, Ownership, Location, Level, District Hospital, Comments
     */
    public function run(): void
    {
        $csvFile = database_path('List of facilities by category Zimbabwe  current.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found at: {$csvFile}");
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Skip header row

        $facilityCount = 0;

        while (($row = fgetcsv($file)) !== false) {
            // Expected columns: Province, Districts, Facilities, Type of Facility, Ownership, Location, Level, District Hospital, Comments
            if (count($row) < 3) {
                continue; // Skip incomplete rows
            }

            $provinceName = trim($row[0] ?? '');
            $districtName = trim($row[1] ?? '');
            $facilityName = trim($row[2] ?? '');
            $typeOfFacility = trim($row[3] ?? 'Clinic');
            $ownership = trim($row[4] ?? 'Government');
            $location = trim($row[5] ?? 'Rural');
            $level = trim($row[6] ?? 'Primary');
            $districtHospital = trim($row[7] ?? 'No');
            $comments = trim($row[8] ?? '');

            // Skip empty rows
            if (empty($provinceName) || empty($districtName) || empty($facilityName)) {
                continue;
            }

            // Normalize province name to match our seeded provinces
            $provinceName = $this->normalizeProvinceName($provinceName);

            // Find province
            $province = Province::where('name', $provinceName)->first();
            if (!$province) {
                $this->command->warn("Province '{$provinceName}' not found. Skipping facility '{$facilityName}'.");
                continue;
            }

            // Find or create district (should already exist from migration)
            $districtName = $this->normalizeDistrictName($districtName);
            $district = District::where('province_id', $province->id)
                ->where('name', $districtName)
                ->first();
            
            if (!$district) {
                $this->command->warn("District '{$districtName}' not found in province '{$provinceName}'. Skipping facility '{$facilityName}'.");
                continue;
            }

            // Determine facility type from type or level column
            $typeName = !empty($typeOfFacility) ? $typeOfFacility : $level;

            // Create or find facility type
            $facilityType = FacilityType::firstOrCreate(
                ['name' => $typeName],
                ['name' => $typeName]
            );

            // Create or find facility
            $facility = Facility::firstOrCreate(
                ['district_id' => $district->id, 'name' => $facilityName],
                [
                    'province_id' => $province->id,
                    'district_id' => $district->id,
                    'facility_type_id' => $facilityType->id,
                    'name' => $facilityName,
                    'ownership' => $ownership,
                    'location' => $location,
                    'level' => $level,
                    'is_district_hospital' => strtolower($districtHospital) === 'yes',
                ]
            );

            $facilityCount++;
        }

        fclose($file);
        $this->command->info("Health facilities seeded successfully! ({$facilityCount} facilities created/updated)");
    }

    /**
     * Normalize district name from CSV to match our district names
     * Handles case variations and common spelling variations
     */
    private function normalizeDistrictName($name): string
    {
        $name = trim($name);
        // Apply title case for consistency
        return ucwords(strtolower($name));
    }

    /**
     * Normalize province name from CSV to match our province names
     * Handles case variations and common spelling variations
     */
    private function normalizeProvinceName($name): string
    {
        $name = strtoupper(trim($name));
        
        // Handle spelling variations and normalize to title case
        $mapping = [
            'MASHONALAND WEST' => 'Mashonaland West',
            'MASHONALAND EAST' => 'Mashonaland East',
            'MASHONALAND CENTRAL' => 'Mashonaland Central',
            'MATABELELAND SOUTH' => 'Matabeleland South',
            'MATABELELAND NORTH' => 'Matabeleland North',
            'MATEBELELAND SOUTH' => 'Matabeleland South',  // Common spelling variation
            'MATEBELELAND NORTH' => 'Matabeleland North',  // Common spelling variation
            'MANICALAND' => 'Manicaland',
            'MASVINGO' => 'Masvingo',
            'MIDLANDS' => 'Midlands',
            'HARARE' => 'Harare',
            'BULAWAYO' => 'Bulawayo',
        ];

        $normalized = $mapping[$name] ?? null;
        
        if ($normalized) {
            return $normalized;
        }

        // Fallback: if not in mapping, apply title case
        return ucwords(strtolower($name));
    }
}
