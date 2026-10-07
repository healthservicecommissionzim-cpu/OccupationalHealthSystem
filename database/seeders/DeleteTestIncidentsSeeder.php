<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeleteTestIncidentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('incidents')
            ->whereIn('reference_number', ['TEST-PND-001', 'TEST-PND-002'])
            ->delete();
        
        $this->command->info('✓ Deleted old test incidents');
    }
}
