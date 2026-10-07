<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application permissions.
     */
    public function run(): void
    {
        $permissions = [
            // Incident permissions
            [
                'name' => 'View Incidents',
                'slug' => 'view_incidents',
                'description' => 'View incident reports',
                'category' => 'incidents',
            ],
            [
                'name' => 'Create Incidents',
                'slug' => 'create_incidents',
                'description' => 'Create new incident reports',
                'category' => 'incidents',
            ],
            [
                'name' => 'Edit Incidents',
                'slug' => 'edit_incidents',
                'description' => 'Edit incident reports',
                'category' => 'incidents',
            ],
            [
                'name' => 'Approve Incidents',
                'slug' => 'approve_incidents',
                'description' => 'Approve incident reports',
                'category' => 'incidents',
            ],
            [
                'name' => 'Delete Incidents',
                'slug' => 'delete_incidents',
                'description' => 'Delete incident reports',
                'category' => 'incidents',
            ],
            [
                'name' => 'Manage Incidents',
                'slug' => 'manage_incidents',
                'description' => 'Manage incident verification and witness statements',
                'category' => 'incidents',
            ],
            
            // First Aid permissions
            [
                'name' => 'View First Aid Records',
                'slug' => 'view_first_aid',
                'description' => 'View first aid records',
                'category' => 'first_aid',
            ],
            [
                'name' => 'Create First Aid Records',
                'slug' => 'create_first_aid',
                'description' => 'Create first aid records',
                'category' => 'first_aid',
            ],
            [
                'name' => 'Manage First Aid Records',
                'slug' => 'manage_first_aid',
                'description' => 'Manage and approve first aid records',
                'category' => 'first_aid',
            ],

            // User management permissions
            [
                'name' => 'View Users',
                'slug' => 'view_users',
                'description' => 'View user profiles',
                'category' => 'users',
            ],
            [
                'name' => 'Manage Users',
                'slug' => 'manage_users',
                'description' => 'Manage user access and roles',
                'category' => 'users',
            ],
            [
                'name' => 'Approve Users',
                'slug' => 'approve_users',
                'description' => 'Approve and reject user applications',
                'category' => 'users',
            ],
            [
                'name' => 'Assign Roles',
                'slug' => 'assign_roles',
                'description' => 'Assign roles to users',
                'category' => 'users',
            ],

            // Report permissions
            [
                'name' => 'View Reports',
                'slug' => 'view_reports',
                'description' => 'View system reports',
                'category' => 'reports',
            ],
            [
                'name' => 'Export Reports',
                'slug' => 'export_reports',
                'description' => 'Export reports to file',
                'category' => 'reports',
            ],

            // Dashboard permissions
            [
                'name' => 'View Dashboard',
                'slug' => 'view_dashboard',
                'description' => 'Access user dashboard',
                'category' => 'dashboard',
            ],

            // CoS Dashboard permissions
            [
                'name' => 'View All Cases',
                'slug' => 'view_all_cases',
                'description' => 'View all cases in CoS dashboard',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'View Timeline',
                'slug' => 'view_timeline',
                'description' => 'View case flow timeline',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'Add Monitoring Note',
                'slug' => 'add_monitoring_note',
                'description' => 'Add governance monitoring notes to cases',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'Send Reminder',
                'slug' => 'send_reminder',
                'description' => 'Send nudge reminders to case holders',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'Escalate Case',
                'slug' => 'escalate_case',
                'description' => 'Escalate cases to next stage',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'Run Compliance Checklist',
                'slug' => 'run_compliance_checklist',
                'description' => 'Run and evaluate compliance checklists',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'Generate Reports',
                'slug' => 'generate_reports',
                'description' => 'Generate CoS reports and analytics',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'Export Data',
                'slug' => 'export_data',
                'description' => 'Export data to CSV/PDF',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'View Audit Log',
                'slug' => 'view_audit_log',
                'description' => 'View case audit trails and logs',
                'category' => 'cos_dashboard',
            ],
            [
                'name' => 'Broadcast Announcement',
                'slug' => 'broadcast_announcement',
                'description' => 'Create and manage announcements',
                'category' => 'cos_dashboard',
            ],

            // Dashboard routing permissions
            [
                'name' => 'Manage Dashboard Mappings',
                'slug' => 'manage_dashboard_mappings',
                'description' => 'Configure role-to-dashboard mappings',
                'category' => 'system',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'description' => $permission['description'],
                    'category' => $permission['category'],
                ]
            );
        }

        // Attach permissions to roles
        $this->attachPermissionsToRoles();
    }

    /**
     * Attach permissions to their respective roles.
     */
    private function attachPermissionsToRoles(): void
    {
        $rolePermissions = [
            'super_admin' => [
                'view_incidents', 'create_incidents', 'edit_incidents', 'approve_incidents', 'delete_incidents',
                'view_first_aid', 'create_first_aid', 'manage_first_aid',
                'view_users', 'manage_users', 'approve_users', 'assign_roles',
                'view_reports', 'export_reports', 'view_dashboard',
                'manage_dashboard_mappings',
            ],
            'supervisor' => [
                'view_incidents', 'create_incidents', 'edit_incidents', 'manage_incidents',
                'view_first_aid', 'create_first_aid',
                'view_users', 'view_reports', 'view_dashboard',
            ],
            'district_hr' => [
                'view_incidents', 'approve_incidents',
                'view_first_aid', 'manage_first_aid',
                'view_users', 'manage_users', 'view_reports', 'view_dashboard',
            ],
            'hospital_hr' => [
                'view_incidents', 'approve_incidents',
                'view_first_aid', 'manage_first_aid',
                'view_users', 'manage_users', 'view_reports', 'view_dashboard',
            ],
            'central_hr' => [
                'view_incidents', 'approve_incidents',
                'view_first_aid', 'manage_first_aid',
                'view_users', 'manage_users', 'view_reports', 'view_dashboard',
            ],
            'hsc_conditions' => [
                'view_incidents', 'view_first_aid',
                'view_users', 'view_reports', 'view_dashboard',
                'view_all_cases', 'view_timeline', 'add_monitoring_note', 'send_reminder',
                'escalate_case', 'run_compliance_checklist', 'generate_reports', 'export_data',
                'view_audit_log', 'broadcast_announcement',
            ],
            'dmo' => [
                'view_incidents', 'approve_incidents',
                'view_first_aid', 'manage_first_aid',
                'view_users', 'view_reports', 'view_dashboard',
            ],
            'pmd' => [
                'view_incidents', 'approve_incidents',
                'view_first_aid', 'manage_first_aid',
                'view_users', 'view_reports', 'export_reports', 'view_dashboard',
            ],
            'cmo' => [
                'view_incidents', 'approve_incidents',
                'view_first_aid', 'manage_first_aid',
                'view_users', 'view_reports', 'export_reports', 'view_dashboard',
            ],
            'mohhc_hr' => [
                'view_incidents', 'view_first_aid',
                'view_users', 'manage_users',
                'view_reports', 'view_dashboard',
            ],
            'labour_social' => [
                'view_incidents', 'view_first_aid',
                'view_users', 'view_reports', 'view_dashboard',
            ],
            'medical_board' => [
                'view_incidents', 'approve_incidents',
                'view_first_aid', 'manage_first_aid',
                'view_users', 'view_reports', 'view_dashboard',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissionSlugs) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                foreach ($permissionSlugs as $slug) {
                    $permission = Permission::where('slug', $slug)->first();
                    if ($permission) {
                        $role->permissions()->syncWithoutDetaching([$permission->id]);
                    }
                }
            }
        }
    }
}
