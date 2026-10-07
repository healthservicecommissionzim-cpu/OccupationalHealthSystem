<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the super admin user.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if (!$superAdminRole) {
            return;
        }

        $user = User::firstOrCreate(
            ['email' => 'admin@hsc.org.zw'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@hsc.org.zw',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'status' => 'approved',
                'approved_at' => now(),
            ]
        );

        // Attach super_admin role if not already attached
        if (!$user->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $user->roles()->attach($superAdminRole->id);
        }
    }
}
