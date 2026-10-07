<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use Faker\Factory as Faker;

class AddFirstAidToTestCasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get the first 3 TEST incidents
        $incidents = Incident::where('reference_number', 'like', 'TEST-%')
            ->orderBy('reference_number', 'asc')
            ->limit(3)
            ->get();

        if ($incidents->isEmpty()) {
            $this->command->error('No TEST incidents found!');
            return;
        }

        $this->command->info('Adding first aid records to ' . $incidents->count() . ' test incidents...');

        foreach ($incidents as $incident) {
            // Add 1-2 first aid records per incident
            $recordCount = $faker->numberBetween(1, 2);
            
            for ($j = 0; $j < $recordCount; $j++) {
                $incident->firstAidRecords()->create([
                    'nurse_name' => $faker->name(),
                    'nurse_license_number' => $faker->numerify('LN########'),
                    'location' => $faker->word() . ' Ward',
                    'injury_description' => $faker->sentence(6),
                    'first_aid_description' => $faker->sentence(8),
                    'severity_level' => $faker->randomElement(['MINOR', 'MODERATE', 'SEVERE']),
                    'time_administered' => $faker->dateTimeBetween('-7 days', 'now'),
                    'notes' => $faker->sentence(5),
                ]);
            }

            // Mark first aid as recorded on the incident
            $incident->update(['first_aid_recorded' => true]);

            $this->command->line('✓ Added ' . $recordCount . ' first aid record(s) to: ' . $incident->reference_number . ' (marked as recorded)');
        }

        $this->command->info('✅ Successfully added first aid records to test incidents!');
    }
}
