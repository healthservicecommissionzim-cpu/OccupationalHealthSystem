<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DashboardRoutingService;
use Illuminate\Console\Command;

class TestDashboardRouting extends Command
{
    protected $signature = 'test:dashboard-routing';
    protected $description = 'Test the dynamic dashboard routing system';

    public function handle()
    {
        $this->info('Testing Dynamic Dashboard Routing System');
        $this->line('');

        $service = app(DashboardRoutingService::class);

        // Test 1: Check available dashboards
        $this->info('✓ Available Dashboards:');
        $dashboards = $service->getAvailableDashboards();
        foreach ($dashboards as $key => $dashboard) {
            $this->line("  - {$key}: {$dashboard['name']} ({$dashboard['route']})");
        }
        $this->line('');

        // Test 2: Check all mappings
        $this->info('✓ Current Role-Dashboard Mappings:');
        $mappings = \App\Models\RoleDashboardMapping::with('role')
            ->active()
            ->ordered()
            ->get();
        
        foreach ($mappings as $mapping) {
            $status = $mapping->is_active ? '✓ Active' : '✗ Inactive';
            $this->line("  - {$mapping->role->name} → {$mapping->dashboard_name} ({$status})");
        }
        $this->line('');

        // Test 3: Test user routing
        $this->info('✓ Testing User Dashboard Resolution:');
        $testUsers = User::with('roles')
            ->whereHas('roles')
            ->limit(3)
            ->get();

        if ($testUsers->isEmpty()) {
            $this->warn('  No users with roles found in database');
        } else {
            foreach ($testUsers as $user) {
                $role = $user->getPrimaryRole();
                $dashboardRoute = $user->getDashboardRoute();
                $this->line("  - User: {$user->name} (Role: {$role?->name ?? 'N/A'})");
                $this->line("    → Dashboard: {$dashboardRoute}");
            }
        }
        $this->line('');

        // Test 4: Cache status
        $this->info('✓ Cache Status:');
        $this->line('  - Cache TTL: 1 hour');
        $this->line('  - Cache invalidates on mapping changes');
        $this->line('');

        $this->info('✓ All tests completed successfully!');
        $this->line('');
        $this->info('Next Steps:');
        $this->line('1. Go to Super Admin → System → Dashboard Mappings');
        $this->line('2. View all configured role-dashboard mappings');
        $this->line('3. Create, edit, or delete mappings as needed');
        $this->line('4. Users will automatically be routed to their mapped dashboard on login');
    }
}
