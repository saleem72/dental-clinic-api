<?php

namespace Database\Seeders;

// use App\Models\V1\Patient;
// use App\Models\V1\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DentalProcedureSeeder::class,
            PatientSeeder::class,
            TreatmentCourseSeeder::class,
            TreatmentSessionSeeder::class,
        ]);
    }
}
