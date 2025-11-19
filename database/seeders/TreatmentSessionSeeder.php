<?php

namespace Database\Seeders;

use App\Models\V1\TreatmentCourse;
use App\Models\V1\TreatmentProcedure;
use App\Models\V1\TreatmentSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $treatmentIds = TreatmentCourse::pluck('id');

        foreach ($treatmentIds as $treatmentId) {
            // Create 1-3 sessions per treatment
            $sessions = TreatmentSession::factory()
                ->count(rand(1, 3))
                ->forTreatment($treatmentId)
                ->create();

            foreach ($sessions as $session) {
                // Create 2-3 procedures per session
                TreatmentProcedure::factory()
                    ->count(rand(2, 3))
                    ->forTreatment($treatmentId)
                    ->forSession($session->id)
                    ->create();
            }
        }

        $this->command->info('all sessions seeded successfully.');
    }
}
