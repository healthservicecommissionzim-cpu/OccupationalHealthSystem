<?php

namespace App\Console\Commands;

use App\Models\Department;
use Illuminate\Console\Command;

class ListDepartments extends Command
{
    protected $signature = 'departments:list';
    protected $description = 'List all departments in the system';

    public function handle()
    {
        $departments = Department::orderBy('name')->get();

        if ($departments->isEmpty()) {
            $this->warn('No departments found.');
            return 1;
        }

        $this->info("All Departments:\n");
        $this->line('='.str_repeat('=', 50).'=');
        $this->line('| ID | NAME'.str_repeat(' ', 40).'|');
        $this->line('='.str_repeat('=', 50).'=');

        foreach ($departments as $dept) {
            $this->line(sprintf('| %-2d | %-45s |', $dept->id, $dept->name));
        }

        $this->line('='.str_repeat('=', 50).'=');

        return 0;
    }
}
