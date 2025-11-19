<?php

namespace Database\Seeders;

use App\Models\V1\Patient;
use App\Models\V1\TreatmentCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::select('id')->get();

        foreach ($patients as $patient) {
            // Randomly pick a status
            $status = rand(0, 1) ? 'completed' : 'active';

            $factory = TreatmentCourse::factory()->forPatient($patient->id);

            if ($status === 'completed') {
                $factory = $factory->completed();
            } else {
                $factory = $factory->active();
            }

            $factory->create();
        }

        $this->command->info('all treatments seeded successfully.');
    }
}
