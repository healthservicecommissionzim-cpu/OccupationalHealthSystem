<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Seed the organizations.
     */
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'Ministry of Health and Child Care',
                'code' => 'MOHCC',
                'description' => 'Ministry of Health and Child Care - Health Facilities',
                'requires_location' => true,
            ],
            [
                'name' => 'Health Service Commission',
                'code' => 'HSC',
                'description' => 'Health Service Commission - National Level',
                'requires_location' => false,
            ],
            [
                'name' => 'Ministry of Labour and Social Welfare',
                'code' => 'MOLSW',
                'description' => 'Ministry of Labour and Social Welfare',
                'requires_location' => false,
            ],
        ];

        foreach ($organizations as $org) {
            Organization::firstOrCreate(['code' => $org['code']], $org);
        }
    }
}
