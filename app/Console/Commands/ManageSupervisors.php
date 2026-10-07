<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Console\Command;

class ManageSupervisors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supervisors:manage 
                            {--list : List all supervisors and their assignments}
                            {--verify : Verify all supervisor assignments}
                            {--fix-email=* : Fix supervisor by email (can use multiple times)}
                            {--assign-email= : Email of supervisor to assign}
                            {--assign-dept= : Department name to assign}
                            {--assign-desig= : Designation name to assign}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Manage and verify supervisor assignments (department and designation)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('list')) {
            return $this->listSupervisors();
        }

        if ($this->option('verify')) {
            return $this->verifySupervisors();
        }

        if ($this->option('assign-email')) {
            return $this->assignSupervisor();
        }

        if ($this->option('fix-email')) {
            return $this->fixSupervisors();
        }

        $this->showHelp();
        return 0;
    }

    /**
     * List all supervisors with their current assignments
     */
    private function listSupervisors()
    {
        $this->info('Fetching all supervisors...\n');

        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })->orWhere(function ($query) {
            $supervisorDesignations = ['Supervisor', 'Senior Supervisor', 'Department Head', 'Manager', 'Matron', 'Sister-in-Charge', 'Principal Nursing Officer', 'Senior Nursing Officer'];
            $query->whereIn('designation_id', 
                Designation::whereIn('name', $supervisorDesignations)->pluck('id')->toArray()
            );
        })
        ->with(['designation', 'department', 'roles'])
        ->orderBy('name')
        ->get();

        if ($supervisors->isEmpty()) {
            $this->warn('No supervisors found.');
            return 1;
        }

        $this->line('='.str_repeat('=', 118).'=');
        $this->line('| NAME'.str_repeat(' ', 24).'| EMAIL'.str_repeat(' ', 29).'| DEPARTMENT'.str_repeat(' ', 15).'| DESIGNATION'.str_repeat(' ', 17).'|');
        $this->line('='.str_repeat('=', 118).'=');

        foreach ($supervisors as $supervisor) {
            $name = substr($supervisor->name, 0, 28);
            $email = substr($supervisor->email, 0, 34);
            $dept = $supervisor->department?->name ? substr($supervisor->department->name, 0, 25) : 'NOT SET';
            $desig = $supervisor->designation?->name ? substr($supervisor->designation->name, 0, 28) : 'NOT SET';

            $this->line(sprintf(
                '| %-30s | %-35s | %-26s | %-29s |',
                $name,
                $email,
                $dept,
                $desig
            ));
        }

        $this->line('='.str_repeat('=', 118).'=');
        $this->newLine();

        $missing = $supervisors->filter(function ($s) {
            return !$s->department_id || !$s->designation_id;
        });

        if ($missing->isNotEmpty()) {
            $this->error("\n⚠️  {$missing->count()} supervisors have missing department or designation assignments!");
            $this->info("\nUse: php artisan supervisors:manage --assign-email=email@example.com --assign-dept=\"Department Name\" --assign-desig=\"Designation Name\"");
        } else {
            $this->info("\n✓ All supervisors have proper assignments!");
        }

        return 0;
    }

    /**
     * Verify supervisor assignments and show issues
     */
    private function verifySupervisors()
    {
        $this->info('Verifying supervisor assignments...\n');

        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })->orWhere(function ($query) {
            $supervisorDesignations = ['Supervisor', 'Senior Supervisor', 'Department Head', 'Manager', 'Matron', 'Sister-in-Charge', 'Principal Nursing Officer', 'Senior Nursing Officer'];
            $query->whereIn('designation_id', 
                Designation::whereIn('name', $supervisorDesignations)->pluck('id')->toArray()
            );
        })
        ->with(['designation', 'department'])
        ->get();

        $issues = [];
        $valid = [];

        foreach ($supervisors as $supervisor) {
            $problemFields = [];

            if (!$supervisor->department_id) {
                $problemFields[] = 'missing department_id';
            }

            if (!$supervisor->designation_id) {
                $problemFields[] = 'missing designation_id';
            }

            if (!empty($problemFields)) {
                $issues[$supervisor->email] = [
                    'name' => $supervisor->name,
                    'problems' => $problemFields,
                ];
            } else {
                $valid[] = $supervisor->email;
            }
        }

        if (!empty($valid)) {
            $this->info("✓ Valid supervisors (" . count($valid) . "):");
            foreach ($valid as $email) {
                $this->line("  • $email");
            }
        }

        if (!empty($issues)) {
            $this->error("\n✗ Supervisors with issues (" . count($issues) . "):");
            foreach ($issues as $email => $issue) {
                $this->line("  • {$issue['name']} ({$email})");
                foreach ($issue['problems'] as $problem) {
                    $this->line("    - $problem");
                }
            }
            $this->newLine();
            $this->info('Fix these using: php artisan supervisors:manage --assign-email=email --assign-dept="Dept" --assign-desig="Designation"');
            return 1;
        }

        $this->info("\n✓ All supervisors are properly configured!");
        return 0;
    }

    /**
     * Assign supervisor to department and designation
     */
    private function assignSupervisor()
    {
        $email = $this->option('assign-email');
        $deptName = $this->option('assign-dept');
        $desigName = $this->option('assign-desig');

        if (!$email || !$deptName || !$desigName) {
            $this->error('Missing required options: --assign-email, --assign-dept, --assign-desig');
            return 1;
        }

        // Find supervisor
        $supervisor = User::where('email', $email)->first();
        if (!$supervisor) {
            $this->error("Supervisor with email '$email' not found.");
            return 1;
        }

        // Find department
        $department = Department::where('name', $deptName)->first();
        if (!$department) {
            $this->error("Department '$deptName' not found.");
            $this->info("Available departments:");
            Department::orderBy('name')->get()->each(fn($d) => $this->line("  • {$d->name}"));
            return 1;
        }

        // Find designation
        $designation = Designation::where('name', $desigName)->first();
        if (!$designation) {
            $this->error("Designation '$desigName' not found.");
            $this->info("Available supervisor-related designations:");
            $supervisorDesignations = ['Supervisor', 'Senior Supervisor', 'Department Head', 'Manager', 'Matron', 'Sister-in-Charge', 'Principal Nursing Officer', 'Senior Nursing Officer'];
            Designation::whereIn('name', $supervisorDesignations)->orderBy('name')->get()->each(fn($d) => $this->line("  • {$d->name}"));
            return 1;
        }

        // Update supervisor
        $supervisor->update([
            'department_id' => $department->id,
            'designation_id' => $designation->id,
        ]);

        $this->info("✓ Successfully assigned supervisor!");
        $this->line("  Name: {$supervisor->name}");
        $this->line("  Email: {$supervisor->email}");
        $this->line("  Department: {$department->name}");
        $this->line("  Designation: {$designation->name}");

        return 0;
    }

    /**
     * Fix multiple supervisors
     */
    private function fixSupervisors()
    {
        $emails = $this->option('fix-email');
        if (empty($emails)) {
            $this->error('No emails provided. Use: --fix-email=email1 --fix-email=email2');
            return 1;
        }

        $this->info('This will interactively help you fix supervisor assignments.\n');

        foreach ($emails as $email) {
            $supervisor = User::where('email', $email)->first();
            if (!$supervisor) {
                $this->warn("Supervisor '$email' not found, skipping.");
                continue;
            }

            $this->line("Fixing: {$supervisor->name} ({$supervisor->email})");

            // Select department
            $departments = Department::orderBy('name')->pluck('name', 'id');
            $deptId = $this->choice('Select department:', $departments->toArray(), $supervisor->department_id);
            
            // Select designation
            $supervisorDesignations = ['Supervisor', 'Senior Supervisor', 'Department Head', 'Manager', 'Matron', 'Sister-in-Charge', 'Principal Nursing Officer', 'Senior Nursing Officer'];
            $designations = Designation::whereIn('name', $supervisorDesignations)->orderBy('name')->pluck('name', 'id');
            $desigId = $this->choice('Select designation:', $designations->toArray(), $supervisor->designation_id);

            $supervisor->update([
                'department_id' => $deptId,
                'designation_id' => $desigId,
            ]);

            $this->info("✓ Updated {$supervisor->name}\n");
        }

        return 0;
    }

    /**
     * Show help text
     */
    private function showHelp()
    {
        $this->info("\nSupervisor Management Command\n");
        $this->line("Options:");
        $this->line("  --list                          List all supervisors and their assignments");
        $this->line("  --verify                        Verify all supervisor assignments");
        $this->line("  --assign-email=<email>          Email of supervisor to assign");
        $this->line("  --assign-dept=<dept>            Department name to assign to");
        $this->line("  --assign-desig=<designation>    Designation name to assign to");
        $this->line("  --fix-email=<email>             Fix supervisor (can use multiple times)");
        
        $this->line("\nExamples:");
        $this->line("  php artisan supervisors:manage --list");
        $this->line("  php artisan supervisors:manage --verify");
        $this->line("  php artisan supervisors:manage --assign-email=supervisor@hsc.org.zw --assign-dept=\"Maternity\" --assign-desig=\"Matron\"");
        $this->line("  p