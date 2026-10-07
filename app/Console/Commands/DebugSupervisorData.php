<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Console\Command;

class DebugSupervisorData extends Command
{
    protected $signature = 'supervisors:debug {--dept-id=35 : Department ID to debug}';
    protected $description = 'Debug supervisor data structure for frontend';

    public function handle()
    {
        $deptId = $this->option('dept-id');

        $this->info("Debugging supervisor data for Department ID: $deptId\n");

        // Get all supervisors (same logic as controller)
        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })->orWhere(function($query) {
            $supervisorDesignations = ['Supervisor', 'Senior Supervisor', 'Department Head', 'Manager', 'Matron', 'Sister-in-Charge', 'Principal Nursing Officer', 'Senior Nursing Officer'];
            $query->whereIn('designation_id', 
                Designation::whereIn('name', $supervisorDesignations)->pluck('id')->toArray()
            );
        })
        ->select('id', 'name', 'designation_id', 'department_id')
        ->with(['designation', 'department'])
        ->get();

        $this->line("Total supervisors found: " . $supervisors->count() . "\n");

        // Show all supervisors
        $this->info("All Supervisors:");
        $this->line(str_repeat('=', 100));
        foreach ($supervisors as $s) {
            $this->line("ID: {$s->id} | Name: {$s->name} | Dept ID: {$s->department_id} | Desig ID: {$s->designation_id}");
            if ($s->department) $this->line("  Department: {$s->department->name}");
            if ($s->designation) $this->line("  Designation: {$s->designation->name}");
        }
        $this->line(str_repeat('=', 100) . "\n");

        // Build supervisorsByDeptIds structure
        $supervisorsByDeptIds = [];
        foreach ($supervisors as $supervisor) {
            if ($supervisor->department_id && $supervisor->designation_id) {
                if (!isset($supervisorsByDeptIds[$supervisor->department_id])) {
                    $supervisorsByDeptIds[$supervisor->department_id] = [];
                }
                if (!in_array($supervisor->designation_id, $supervisorsByDeptIds[$supervisor->department_id])) {
                    $supervisorsByDeptIds[$supervisor->department_id][] = $supervisor->designation_id;
                }
            }
        }

        $this->info("supervisorsByDeptIds structure (passed to frontend):");
        $this->line(json_encode($supervisorsByDeptIds, JSON_PRETTY_PRINT) . "\n");

        // Build supervisorsByDesignation structure
        $supervisorsByDesignation = [];
        foreach ($supervisors as $supervisor) {
            if ($supervisor->designation_id) {
                if (!isset($supervisorsByDesignation[$supervisor->designation_id])) {
                    $supervisorsByDesignation[$supervisor->designation_id] = [];
                }
                $supervisorsByDesignation[$supervisor->designation_id][] = [
                    'id' => $supervisor->id,
                    'name' => $supervisor->name,
                ];
            }
        }

        $this->info("supervisorsByDesignation structure (passed to frontend):");
        $this->line(json_encode($supervisorsByDesignation, JSON_PRETTY_PRINT) . "\n");

        // Check specific department
        $dept = Department::find($deptId);
        if (!$dept) {
            $this->error("Department with ID $deptId not found!");
            return 1;
        }

        $this->info("=== ANALYSIS FOR DEPARTMENT: {$dept->name} (ID: $deptId) ===\n");

        if (isset($supervisorsByDeptIds[$deptId])) {
            $designationIds = $supervisorsByDeptIds[$deptId];
            $this->line("✓ Found " . count($designationIds) . " designation(s) for this department:");
            foreach ($designationIds as $desigId) {
                $desig = Designation::find($desigId);
                $this->line("  • ID: $desigId | Name: {$desig?->name}");
                
                if (isset($supervisorsByDesignation[$desigId])) {
                    $supervisorCount = count($supervisorsByDesignation[$desigId]);
                    $this->line("    ├─ $supervisorCount supervisor(s) with this designation");
                    foreach ($supervisorsByDesignation[$desigId] as $sup) {
                        $this->line("    │  └─ {$sup['name']} (ID: {$sup['id']})");
                    }
                }
            }
        } else {
            $this->error("✗ No designations found for this department!");
            $this->warn("\nSupervisors in this department:");
            $supervisorsInDept = $supervisors->where('department_id', $deptId);
            if ($supervisorsInDept->isNotEmpty()) {
                foreach ($supervisorsInDept as $sup) {
                    $this->line("  • {$sup->name}");
                    $this->line("    - Dept ID: {$sup->department_id}");
                    $this->line("    - Desig ID: {$sup->designation_id}");
                    if (!$sup->department_id) {
                        $this->line("    ⚠️  Missing department_id!");
                    }
                    if (!$sup->designation_id) {
                        $this->line("    ⚠️  Missing designation_id!");
                    }
                }
            } else {
                $this->warn("  No supervisors assigned to this department");
            }
        }

        return 0;
    }
}
