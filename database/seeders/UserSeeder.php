<?php

namespace Database\Seeders;

use App\Models\V1\Role;
use App\Models\V1\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $users = [
            [
                'username' => 'manager',
                'name' => 'dr Michel Jackson',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('password123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['manager', 'dentist'],
            ],
            [
                'username' => 'dr_jack',
                'name' => 'dr Jack Gibson',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('password123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['dentist'],
            ],
            [
                'username' => 'john_doe',
                'name' => 'John Doe',
                'email' => null,
                'phone' => null,
                'password' => Hash::make('password123'),
                'is_active' => true,
                'must_change_password' => true,
                'roles' => ['patient'],
            ],
        ];

        foreach ($users as $userData) {
            // Extract roles
            $roleNames = $userData['roles'];
            unset($userData['roles']);

            // Create user
            $user = User::create($userData);

            // Attach roles by name
            $roles = Role::whereIn('name', $roleNames)->pluck('id');
            $user->roles()->attach($roles);
        }

        $this->command->info('all users seeded successfully.');
    }
}
