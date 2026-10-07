<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

// Get the first (and only) admin user
$admin = User::first();

if (!$admin) {
    echo "No admin user found!\n";
    exit(1);
}

// Get super_admin role
$superAdminRole = Role::where('name', 'super_admin')->first();

if (!$superAdminRole) {
    echo "super_admin role not found!\n";
    exit(1);
}

// Assign super_admin role to the user
$admin->roles()->sync([$superAdminRole->id]);
echo "✓ Super admin role assigned to admin user\n";

// Assign all permissions to the super_admin role
$allPermissions = Permission::all();
$superAdminRole->permissions()->sync($allPermissions->pluck('id'));
echo "✓ All " . $allPermissions->count() . " permissions assigned to super_admin role\n";

// Verify the setup
$admin->refresh();
echo "\n=== Admin Setup Verified ===\n";
echo "User: " . $admin->name . " (" . $admin->email . ")\n";
echo "Roles assigned: " . $admin->roles()->pluck('name')->implode(', ') . "\n";
echo "Total permissions via role: " . $admin->roles()->with('permissions')->get()->flatMap->permissions->unique('id')->count() . "\n";
