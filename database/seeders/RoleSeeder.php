<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application roles.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access and administration rights',
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Supervises incidents and manages local operations',
            ],
            [
                'name' => 'district_hr',
                'display_name' => 'District HR Officer',
                'description' => 'Reviews incidents from clinics and district hospitals',
            ],
            [
                'name' => 'hospital_hr',
                'display_name' => 'Hospital HR Officer',
                'description' => 'Reviews incidents from general and provincial hospitals',
            ],
            [
                'name' => 'central_hr',
                'display_name' => 'Central HR Officer',
                'description' => 'Reviews incidents from central hospitals',
            ],
            [
                'name' => 'dmo',
                'display_name' => 'District Medical Officer',
                'description' => 'Reviews and processes incident reports',
            ],
            [
                'name' => 'pmd',
                'display_name' => 'Provincial Medical Director',
                'description' => 'Provincial level medical management',
            ],
            [
                'name' => 'cmo',
                'display_name' => 'Chief Medical Officer',
                'description' => 'Chief medical officer overseeing central hospitals',
            ],
            [
                'name' => 'mohhc_hr',
                'display_name' => 'MoHCC HR Officer',
                'description' => 'HR management and employee records',
            ],
            [
                'name' => 'labour_social',
                'display_name' => 'Labour & Social Affairs',
                'description' => 'Labour law and social compensation management',
            ],
            [
                'name' => 'medical_board',
                'display_name' => 'Medical Board Member',
                'description' => 'Medical board review and decisions',
            ],
            [
                'name' => 'hsc_conditions',
                'display_name' => 'HSC Conditions Officer',
                'description' => 'Occupational health and safety conditions monitoring',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
        }
    }
}
