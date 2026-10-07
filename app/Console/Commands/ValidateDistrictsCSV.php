<?php

namespace App\Console\Commands;

use App\Models\District;
use App\Models\Province;
use Illuminate\Console\Command;

class ValidateDistrictsCSV extends Command
{
    protected $signature = 'districts:validate-csv';
    protected $description = 'Validate and preview districts from health_facilities.csv';

    public function handle()
    {
        $csvFile = database_path('List of facilities by category Zimbabwe  current.csv');

        if (!file_exists($csvFile)) {
            $this->error("CSV file not found at: {$csvFile}");
            return 1;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file);
        
        $this->info('CSV Header: ' . implode(' | ', $header));
        $this->newLine();

        $districts = [];
        $errors = [];
        $rowCount = 0;

        while (($row = fgetcsv($file)) !== false) {
            $rowCount++;
            
            if (count($row) < 3) {
                continue;
            }

            $provinceName = trim($row[0] ?? '');
            $districtName = trim($row[1] ?? '');
            $facilityName = trim($row[2] ?? '');

            if (empty($provinceName) || empty($districtName) || empty($facilityName)) {
                continue;
            }

            $key = "{$provinceName} | {$districtName}";
            
            if (!isset($districts[$key])) {
                $districts[$key] = [];
            }
            
            $districts[$key][] = $facilityName;
        }

        fclose($file);

        $this->info("Total rows processed: {$rowCount}");
        $this->info("Unique Province-District combinations: " . count($districts));
        $this->newLine();

        $this->table(
            ['Province | District', 'Facility Count'],
            array_map(fn($k, $v) => [$k, count($v)], array_keys($districts), array_values($districts))
        );

        $this->newLine();
        $this->info('Run "php artisan db:seed --class=ZimbabweDistrictsSeeder" to seed districts');
        $this->info('Run "php artisan db:seed --class=UpdatedHealthFacilitiesSeeder" to seed facilities');

        return 0;
    }
}
