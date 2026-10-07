<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OrganizationSeeder::class,
            RoleSeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            ZimbabweDistrictsSeeder::class,
            UpdatedHealthFacilitiesSeeder::class,
            PermissionSeeder::class,
            SuperAdminSeeder::class,
            IncidentSeeder::class,
            AuditLogSeeder::class,
            JohnMashizhaHRSeeder::class,
        ]);
    }
}
