<?php

namespace App\Services;

use App\Models\V1\Role;
use App\Models\V1\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersService {
    public function createPatient($currentUserId, $validated)  {

        $created = null;

        DB::transaction(function () use ($validated, &$created, $currentUserId) {
            // 1. Create user
            $createdFields = Arr::only($validated, ['name', 'email', 'phone', 'username']);
            $createdFields['password'] = Hash::make('password123');
            $createdFields['created_by'] = $currentUserId;
            $created = User::create($createdFields);

            // 2. Assign 'patient' role
            $patientRole = Role::where('name', 'patient')->firstOrFail();
            $created->roles()->sync([$patientRole->id]);

            // 3. Create patient profile
            $created->patient()->create([
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'medical_history' => $validated['medical_history'] ?? null,
                'patient_code' => $validated['patient_code'] ?? null,
                'medical_notes' => $validated['medical_notes'] ?? null,
            ]);
        });

        return $created;

    }


    public function createDentist($currentUserId, $validated)  {

        $created = null;

        DB::transaction(function () use ($validated, &$created, $currentUserId) {
            // 1. Create user
            $createdFields = Arr::only($validated, ['name', 'email', 'phone', 'username']);
            $createdFields['password'] = Hash::make('password123');
            $createdFields['created_by'] = $currentUserId;
            $created = User::create($createdFields);

            // 2. Assign 'patient' role
            $patientRole = Role::where('name', 'dentist')->firstOrFail();
            $created->roles()->sync([$patientRole->id]);

            // 3. Create patient profile
            $created->dentist()->create([
                'license_number' => $validated['license_number'],
                'specialization' => $validated['specialization'],
                'bio' => $validated['bio'],
                'commission_rate' => $validated['commission_rate'],
                'is_available' => 'true'
            ]);
        });

        return $created;

    }
}
