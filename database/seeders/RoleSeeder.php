<?php

namespace Database\Seeders;

use App\Models\V1\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'manager', 'description' => 'Oversees clinic operations, users, and reports.'],
            ['name' => 'dentist', 'description' => 'Manages patient treatments, records, and appointments.'],
            ['name' => 'patient', 'description' => 'Accesses personal records and manages appointments.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }

         $this->command->info('all roles seeded successfully.');
    }
}
