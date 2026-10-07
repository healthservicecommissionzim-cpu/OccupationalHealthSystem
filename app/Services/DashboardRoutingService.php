<?php

namespace App\Services;

use App\Models\RoleDashboardMapping;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardRoutingService
{
    private const CACHE_TTL = 3600; // 1 hour
    private const CACHE_KEY = 'role_dashboard_mappings:';

    /**
     * Get the primary dashboard for a user based on their roles.
     * Returns the highest priority (lowest sort_order) active dashboard.
     *
     * @param User $user
     * @return array|null {route, name, description, controller_action}
     */
    public function getUserDashboard(User $user): ?array
    {
        try {
            // Get user's roles ordered by mapping priority
            $userRoles = $user->roles()
                ->with('dashboardMapping')
                ->get();

            if ($userRoles->isEmpty()) {
                Log::warning('User has no roles', ['user_id' => $user->id]);
                return null;
            }

            // Get active mappings for user's roles, ordered by priority
            $mappings = RoleDashboardMapping::whereIn('role_id', $userRoles->pluck('id'))
                ->active()
                ->ordered()
                ->first();

            if (!$mappings) {
                Log::info('No dashboard mapping found for user roles', [
                    'user_id' => $user->id,
                    'roles' => $userRoles->pluck('name')->toArray(),
                ]);
                return null;
            }

            return [
                'route' => $mappings->dashboard_route,
                'name' => $mappings->dashboard_name,
                'description' => $mappings->description,
                'controller_action' => $mappings->controller_action,
            ];
        } catch (\Exception $e) {
            Log::error('Error getting user dashboard', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get all available dashboard mappings (for admin display).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMappings()
    {
        return Cache::remember(self::CACHE_KEY . 'all', self::CACHE_TTL, function () {
            return RoleDashboardMapping::with('role')
                ->active()
                ->ordered()
                ->get();
        });
    }

    /**
     * Get mappings for a specific role.
     *
     * @param int $roleId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMappingsForRole($roleId)
    {
        return Cache::remember(self::CACHE_KEY . "role:{$roleId}", self::CACHE_TTL, function () use ($roleId) {
            return RoleDashboardMapping::forRole($roleId)
                ->active()
                ->ordered()
                ->get();
        });
    }

    /**
     * Create a new dashboard mapping.
     *
     * @param int $roleId
     * @param string $dashboardName
     * @param string $dashboardRoute
     * @param array $data Additional data (description, controller_action, sort_order)
     * @return RoleDashboardMapping
     */
    public function createMapping(int $roleId, string $dashboardName, string $dashboardRoute, array $data = []): RoleDashboardMapping
    {
        try {
            $mapping = RoleDashboardMapping::create([
                'role_id' => $roleId,
                'dashboard_name' => $dashboardName,
                'dashboard_route' => $dashboardRoute,
                'controller_action' => $data['controller_action'] ?? null,
                'description' => $data['description'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
            ]);

            $this->invalidateCache();

            Log::info('Dashboard mapping created', [
                'role_id' => $roleId,
                'dashboard_name' => $dashboardName,
                'mapping_id' => $mapping->id,
            ]);

            return $mapping;
        } catch (\Exception $e) {
            Log::error('Failed to create dashboard mapping', [
                'role_id' => $roleId,
                'dashboard_name' => $dashboardName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing dashboard mapping.
     *
     * @param int $mappingId
     * @param array $data
     * @return RoleDashboardMapping
     */
    public function updateMapping(int $mappingId, array $data): RoleDashboardMapping
    {
        try {
            $mapping = RoleDashboardMapping::findOrFail($mappingId);

            $mapping->update([
                'dashboard_route' => $data['dashboard_route'] ?? $mapping->dashboard_route,
                'controller_action' => $data['controller_action'] ?? $mapping->controller_action,
                'description' => $data['description'] ?? $mapping->description,
                'sort_order' => $data['sort_order'] ?? $mapping->sort_order,
                'is_active' => $data['is_active'] ?? $mapping->is_active,
            ]);

            $this->invalidateCache();

            Log::info('Dashboard mapping updated', [
                'mapping_id' => $mappingId,
                'role_id' => $mapping->role_id,
            ]);

            return $mapping;
        } catch (\Exception $e) {
            Log::error('Failed to update dashboard mapping', [
                'mapping_id' => $mappingId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a dashboard mapping.
     *
     * @param int $mappingId
     * @return bool
     */
    public function deleteMapping(int $mappingId): bool
    {
        try {
            $mapping = RoleDashboardMapping::findOrFail($mappingId);
            $roleId = $mapping->role_id;

            $mapping->delete();
            $this->invalidateCache();

            Log::info('Dashboard mapping deleted', [
                'mapping_id' => $mappingId,
                'role_id' => $roleId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete dashboard mapping', [
                'mapping_id' => $mappingId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Disable a dashboard mapping.
     *
     * @param int $mappingId
     * @return RoleDashboardMapping
     */
    public function disableMapping(int $mappingId): RoleDashboardMapping
    {
        return $this->updateMapping($mappingId, ['is_active' => false]);
    }

    /**
     * Enable a dashboard mapping.
     *
     * @param int $mappingId
     * @return RoleDashboardMapping
     */
    public function enableMapping(int $mappingId): RoleDashboardMapping
    {
        return $this->updateMapping($mappingId, ['is_active' => true]);
    }

    /**
     * Check if a role has a dashboard mapping.
     *
     * @param int $roleId
     * @return bool
     */
    public function roleMapped(int $roleId): bool
    {
        return RoleDashboardMapping::forRole($roleId)->active()->exists();
    }

    /**
     * Get available dashboard options (for forms).
     *
     * @return array
     */
    public function getAvailableDashboards(): array
    {
        return [
            'supervisor' => [
                'name' => 'Supervisor Dashboard',
                'route' => '/dashboards/supervisor',
                'description' => 'Supervisor incident management dashboard',
            ],
            'hr' => [
                'name' => 'HR Dashboard',
                'route' => '/dashboards/hr',
                'description' => 'HR case review and approval dashboard',
            ],
            'district_hr' => [
                'name' => 'District HR Dashboard',
                'route' => '/dashboards/district-hr',
                'description' => 'District-level HR dashboard',
            ],
            'hospital_hr' => [
                'name' => 'Hospital HR Dashboard',
                'route' => '/dashboards/hospital-hr',
                'description' => 'Hospital-level HR dashboard',
            ],
            'central_hr' => [
                'name' => 'Central HR Dashboard',
                'route' => '/dashboards/central-hr',
                'description' => 'Central-level HR dashboard',
            ],
            'clinical_executive' => [
                'name' => 'Clinical Executive Dashboard',
                'route' => '/clinical-executive/dashboard',
                'description' => 'Clinical executive review and case management',
            ],
            'dmo' => [
                'name' => 'DMO Dashboard',
                'route' => '/dashboards/dmo',
                'description' => 'District Medical Officer dashboard',
            ],
            'cmo' => [
                'name' => 'CMO Dashboard',
                'route' => '/dashboards/cmo',
                'description' => 'Chief Medical Officer dashboard',
            ],
            'pmd' => [
                'name' => 'PMD Dashboard',
                'route' => '/dashboards/pmd',
                'description' => 'Provincial Medical Director dashboard',
            ],
            'mohhc_hr' => [
                'name' => 'MOHHC HR Dashboard',
                'route' => '/dashboards/mohhc-hr',
                'description' => 'Ministry of Health HR dashboard',
            ],
            'labour_social' => [
                'name' => 'Labour & Social Dashboard',
                'route' => '/dashboards/labour-social',
                'description' => 'Labour and social welfare dashboard',
            ],
            'medical_board' => [
                'name' => 'Medical Board Dashboard',
                'route' => '/dashboards/medical-board',
                'description' => 'Medical board review dashboard',
            ],
            'hsc_conditions' => [
                'name' => 'Conditions of Service',
                'route' => '/dashboards/hsc-conditions',
                'description' => 'Governance and oversight dashboard for CoS staff',
            ],
            'super_admin' => [
                'name' => 'Super Admin Dashboard',
                'route' => '/dashboards/super-admin',
                'description' => 'Super administrator configuration panel',
            ],
        ];
    }

    /**
     * Invalidate all cache entries for dashboard mappings.
     *
     * @return void
     */
    public function invalidateCache(): void
    {
        Cache::forget(self::CACHE_KEY . 'all');
        // Also clear role-specific caches
        $allMappings = RoleDashboardMapping::pluck('role_id')->unique();
        foreach ($allMappings as $roleId) {
            Cache::forget(self::CACHE_KEY . "role:{$roleId}");
        }
    }
}
