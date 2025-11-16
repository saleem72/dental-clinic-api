<?php

namespace Database\Seeders;

use App\Models\V1\DentalProcedure;
use Illuminate\Database\Seeder;


class DentalProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $procedures = [
            ['name' => 'Comprehensive Oral Evaluation', 'dental_code' => 'D0150', 'fee' => 54.09],
            ['name' => 'Periodic Oral Evaluation', 'dental_code' => 'D0120', 'fee' => 35.00],
            ['name' => 'Prophylaxis – Adult', 'dental_code' => 'D1110', 'fee' => 70.00],
            ['name' => 'Prophylaxis – Child', 'dental_code' => 'D1120', 'fee' => 50.00],
            ['name' => 'Amalgam – One Surface, Primary or Permanent', 'dental_code' => 'D2140', 'fee' => 120.00],
            ['name' => 'Amalgam – Two Surfaces, Primary or Permanent', 'dental_code' => 'D2150', 'fee' => 150.00],
            ['name' => 'Composite – One Surface, Anterior', 'dental_code' => 'D2330', 'fee' => 180.00],
            ['name' => 'Composite – Two Surfaces, Anterior', 'dental_code' => 'D2331', 'fee' => 220.00],
            ['name' => 'Composite – Three Surfaces, Anterior', 'dental_code' => 'D2332', 'fee' => 250.00],
            ['name' => 'Fluoride Treatment – Child', 'dental_code' => 'D1208', 'fee' => 35.00],
            ['name' => 'Sealant – Per Tooth', 'dental_code' => 'D1351', 'fee' => 40.00],
            ['name' => 'Panoramic Radiograph', 'dental_code' => 'D0330', 'fee' => 80.00],
            ['name' => 'Periodical Radiograph – Single', 'dental_code' => 'D0220', 'fee' => 25.00],
            ['name' => 'Extraction – Single Tooth', 'dental_code' => 'D7140', 'fee' => 150.00],
            ['name' => 'Root Canal – Anterior', 'dental_code' => 'D3310', 'fee' => 400.00],
            ['name' => 'Root Canal – Bicuspid', 'dental_code' => 'D3320', 'fee' => 500.00],
            ['name' => 'Root Canal – Molar', 'dental_code' => 'D3330', 'fee' => 600.00],
            ['name' => 'Crown – Porcelain Fused to Metal', 'dental_code' => 'D2740', 'fee' => 900.00],
            ['name' => 'Crown – All Ceramic', 'dental_code' => 'D2790', 'fee' => 1000.00],
            ['name' => 'Scaling and Root Planing – Per Quadrant', 'dental_code' => 'D4341', 'fee' => 150.00],
        ];

        foreach ($procedures as $procedure) {
            DentalProcedure::updateOrCreate(
                ['dental_code' => $procedure['dental_code']],
                $procedure
            );
        }

         $this->command->info('all dental procedures seeded successfully.');
    }
}
