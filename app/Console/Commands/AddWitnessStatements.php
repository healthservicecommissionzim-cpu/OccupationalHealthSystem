<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Incident;
use App\Models\IncidentWitnessToken;

class AddWitnessStatements extends Command
{
    protected $signature = 'witnesses:add {reference_number}';
    protected $description = 'Add 2 witness statements to an incident';

    public function handle()
    {
        $referenceNumber = $this->argument('reference_number');
        
        $incident = Incident::where('reference_number', $referenceNumber)->first();

        if (!$incident) {
            $this->error("Incident not found: {$referenceNumber}");
            return;
        }

        $this->info("Found incident: {$incident->reference_number}");
        $this->info("Worker: {$incident->first_name} {$incident->surname}");
        $this->info("Facility: {$incident->facility->name}\n");

        // Create first witness
        $witness1 = IncidentWitnessToken::create([
            'incident_id' => $incident->id,
            'witness_name' => 'John Sibanda',
            'phone_number' => '+263712345678',
            'token' => \Illuminate\Support\Str::uuid()->toString(),
            'statement' => 'I witnessed the incident on the date mentioned. The worker was performing their duties when the incident occurred. I can confirm the sequence of events as reported.',
            'statement_submitted_at' => now(),
            'token_sent_at' => now(),
            'token_expired_at' => now(),
            'is_expired' => true,
        ]);

        $this->info("✓ Created Witness 1: {$witness1->witness_name}");

        // Create second witness
        $witness2 = IncidentWitnessToken::create([
            'incident_id' => $incident->id,
            'witness_name' => 'Mary Muzenda',
            'phone_number' => '+263787654321',
            'token' => \Illuminate\Support\Str::uuid()->toString(),
            'statement' => 'I was present at the facility when the incident happened. The circumstances and timeline match what was reported by the worker and other witnesses.',
            'statement_submitted_at' => now(),
            'token_sent_at' => now(),
            'token_expired_at' => now(),
            'is_expired' => true,
        ]);

        $this->info("✓ Created Witness 2: {$witness2->witness_name}");

        $this->info("\n✓ Successfully added 2 witness statements to incident {$incident->reference_number}");
    }
}
