<?php

namespace Database\Seeders;

use App\Models\V1\Patient;
use App\Models\V1\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(5)
            ->has(Patient::factory()->withDentist(1), 'patient')
            ->create();

        User::factory()
            ->count(7)
            ->has(Patient::factory()->withDentist(2), 'patient')
            ->create();

        User::factory()
            ->count(4)
            ->has(Patient::factory()->withDentist(3), 'patient')
            ->create();

        $this->command->info('all patients seeded successfully.');
    }
}
