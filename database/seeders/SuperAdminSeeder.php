<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        $existingAdmin = User::where('email', 'admin@hsc.org.zw')->first();
        
        if ($existingAdmin) {
            return; // Skip if already exists
        }

        // Create or get super_admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if (!$superAdminRole) {
            $superAdminRole = Role::create([
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Super Administrator with full system access',
            ]);
        }

        // Create super admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@hsc.org.zw',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => 'approved',
            'approved_at' => now(),
            'phone_number' => '+263712345678',
        ]);

        // Assign super_admin role
        $superAdmin->roles()->attach($superAdminRole->id);
    }
}
