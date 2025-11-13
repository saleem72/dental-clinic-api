<?php

namespace Database\Seeders;

use App\Models\V1\Role;
use App\Models\V1\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin
            [
                'username' => 'admin',
                'name' => 'Admin',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('password123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['manager'],
            ],

            // Dentists
            [
                'username' => 'dentist1',
                'name' => 'Dr. Alice Smith',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('dentist123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['dentist'],
                'dentist' => [
                    'license_number' => 'DENT-1001',
                    'specialization' => 'Orthodontics',
                    'bio' => 'Experienced orthodontist with 5 years of practice.',
                    'commission_rate' => 10.50,
                    'is_available' => true,
                ],
            ],
            [
                'username' => 'dentist2',
                'name' => 'Dr. Bob Johnson',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('dentist123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['dentist'],
                'dentist' => [
                    'license_number' => 'DENT-1002',
                    'specialization' => 'Endodontics',
                    'bio' => 'Root canal specialist.',
                    'commission_rate' => 12.00,
                    'is_available' => true,
                ],
            ],
            [
                'username' => 'dentist3',
                'name' => 'Dr. Carol Lee',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('dentist123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['dentist'],
                'dentist' => [
                    'license_number' => 'DENT-1003',
                    'specialization' => 'Pediatric Dentistry',
                    'bio' => 'Caring dentist specialized in children.',
                    'commission_rate' => 9.75,
                    'is_available' => true,
                ],
            ],

            // Patients
            [
                'username' => 'patient1',
                'name' => 'John Doe',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('patient123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1001',
                    'gender' => 'male',
                    'date_of_birth' => '1990-05-12',
                    'medical_notes' => null,
                    'medical_history' => null,
                    'dentist_id' => 1, // assign to dentist1
                ],
            ],
            [
                'username' => 'patient2',
                'name' => 'Jane Smith',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('patient123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1002',
                    'gender' => 'female',
                    'date_of_birth' => '1988-11-23',
                    'medical_notes' => null,
                    'medical_history' => null,
                    'dentist_id' => 2, // assign to dentist2
                ],
            ],
            // Sample 3
            [
                'username' => 'patient3',
                'name' => 'Michael Johnson',
                'email' => 'michael.j@example.com',
                'phone' => '0123456789',
                'password' => Hash::make('patient123'),
                'is_active' => true,
                'must_change_password' => false,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1003',
                    'gender' => 'male',
                    'date_of_birth' => '1975-01-30',
                    'medical_notes' => 'History of high blood pressure.',
                    'medical_history' => null,
                    'dentist_id' => 1, // assign to dentist1
                ],
            ],
            // Sample 4
            [
                'username' => 'patient4',
                'name' => 'Emily Davis',
                'email' => 'emily.d@example.com',
                'phone' => null,
                'password' => Hash::make('patient123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1004',
                    'gender' => 'female',
                    'date_of_birth' => '2001-08-15',
                    'medical_notes' => null,
                    'medical_history' => 'Wisdom teeth extracted 2021.',
                    'dentist_id' => 2, // assign to dentist2
                ],
            ],
            // Sample 5
            [
                'username' => 'patient5',
                'name' => 'David Wilson',
                'email' => null,
                'phone' => '0555123456',
                'password' => Hash::make('patient123'),
                'is_active' => false, // Inactive user example
                'must_change_password' => false,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1005',
                    'gender' => 'male',
                    'date_of_birth' => '1965-12-01',
                    'medical_notes' => null,
                    'medical_history' => null,
                    'dentist_id' => 1, // assign to dentist1
                ],
            ],
            // Sample 6
            [
                'username' => 'patient6',
                'name' => 'Sarah Brown',
                'email' => 'sarah.b@example.com',
                'phone' => null,
                'password' => Hash::make('patient123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1006',
                    'gender' => 'female',
                    'date_of_birth' => '1992-03-10',
                    'medical_notes' => null,
                    'medical_history' => null,
                    'dentist_id' => 2, // assign to dentist2
                ],
            ],
            // Sample 7
            [
                'username' => 'patient7',
                'name' => 'Chris Garcia',
                'email' => null,
                'phone' => '0123987654',
                'password' => Hash::make('patient123'),
                'is_active' => true,
                'must_change_password' => false,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1007',
                    'gender' => 'male',
                    'date_of_birth' => '1981-06-25',
                    'medical_notes' => 'Allergic to penicillin.',
                    'medical_history' => null,
                    'dentist_id' => 1, // assign to dentist1
                ],
            ],
            // Sample 8
            [
                'username' => 'patient8',
                'name' => 'Laura Miller',
                'email' => 'laura.m@example.com',
                'phone' => null,
                'password' => Hash::make('patient123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1008',
                    'gender' => 'female',
                    'date_of_birth' => '1995-09-18',
                    'medical_notes' => null,
                    'medical_history' => 'Routine checkup needed.',
                    'dentist_id' => 2, // assign to dentist2
                ],
            ],
            // Sample 9
            [
                'username' => 'patient9',
                'name' => 'James Rodriguez',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('patient123'),
                'is_active' => true,
                'must_change_password' => false,
                'roles' => ['patient'],
                'patient' => [
                    'patient_code' => 'P1009',
                    'gender' => 'male',
                    'date_of_birth' => '1970-04-03',
                    'medical_notes' => null,
                    'medical_history' => null,
                    'dentist_id' => 1, // assign to dentist1
                ],
            ],
        ];

        foreach ($users as $userData) {
            $roleNames = $userData['roles'];
            unset($userData['roles']);

            $relatedData = null;
            if (isset($userData['dentist'])) {
                $relatedData = ['dentist' => $userData['dentist']];
                unset($userData['dentist']);
            } elseif (isset($userData['patient'])) {
                $relatedData = ['patient' => $userData['patient']];
                unset($userData['patient']);
            }

            // Create user
            $user = User::create($userData);

            // Attach roles
            $roles = Role::whereIn('name', $roleNames)->pluck('id');
            $user->roles()->attach($roles);

            // Create related dentist/patient record
            if (isset($relatedData['dentist'])) {
                $user->dentist()->create($relatedData['dentist']);
            } elseif (isset($relatedData['patient'])) {
                $user->patient()->create($relatedData['patient']);
            }
        }

        $this->command->info('All users, dentists, and patients seeded successfully.');
    }
}
