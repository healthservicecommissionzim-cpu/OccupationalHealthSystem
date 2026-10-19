<?php

namespace Database\Seeders;

use App\Models\FacilityType;
use Illuminate\Database\Seeder;

class FacilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Central Hospital',
            'Provincial Hospital',
            'General Hospital',
            'District Hospital',
            'Primary Clinic',
            'Poly-Clinic',
            'Rural Health Centre',
            'Health Post',
        ];

        foreach ($types as $type) {
            FacilityType::firstOrCreate(['name' => $type]);
        }
    }
}
