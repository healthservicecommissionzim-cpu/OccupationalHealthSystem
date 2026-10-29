<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $departments = [
            'Secretary & Commissioner Office',
            'Conditions Of Service & Industrial Relations',
            'Human Resources',
            'Performance Improvement & Development',
            'Finance & Administration',
            'Monitoring & Evaluation',
            'Public Relations',
            'Procurement Management Unit',
            'Discipline Unit',
            'Audit Unit',
            'Legal Unit',
            'Information Communication Technology Unit',
        ];

        foreach ($departments as $department) {
            DB::table('departments')->insertOrIgnore([
                'name' => $department,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $departments = [
            'Secretary & Commissioner Office',
            'Conditions Of Service & Industrial Relations',
            'Human Resources',
            'Performance Improvement & Development',
            'Finance & Administration',
            'Monitoring & Evaluation',
            'Public Relations',
            'Procurement Management Unit',
            'Discipline Unit',
            'Audit Unit',
            'Legal Unit',
            'Information Communication Technology Unit',
        ];

        foreach ($departments as $department) {
            DB::table('departments')->where('name', $department)->delete();
        }
    }
};
