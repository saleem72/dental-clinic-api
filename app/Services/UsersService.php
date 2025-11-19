<?php

namespace App\Services;

use App\Helpers\PatientCodeGenerator;
use App\Helpers\UsernameGenerator;
use App\Models\V1\ActionRequest;
use App\Models\V1\Patient;
use App\Models\V1\Role;
use App\Models\V1\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersService
{
    /**
     * Shared function: create User + Patient profile
     */
    public function createPatientUserAndProfile(array $userData, array $patientData): Patient
    {
        return DB::transaction(function () use ($userData, $patientData) {

            // 1. Create user
            $user = User::create($userData);

            // 2. Attach patient role
            $role = Role::where('name', 'patient')->firstOrFail();
            $user->roles()->sync([$role->id]);

            // 3. Create patient profile
            return $user->patient()->create($patientData);
        });
    }

    /**
     * Direct patient creation from CreatePatientRequest
     */
    public function createPatient(int $currentUserId, array $validated): Patient
    {
        $userData = [
            'name'       => $validated['name'],
            'username'   => UsernameGenerator::generatePatientUsername(),
            'phone'      => $validated['phone'],
            'email'      => $validated['email'],
            'password'   => Hash::make('password123'),
            'created_by' => $currentUserId,
        ];

        $patientData = [
            'date_of_birth'   => $validated['date_of_birth'] ?? null,
            'gender'          => $validated['gender'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'medical_notes'   => $validated['medical_notes'] ?? null,
            'patient_code'    => PatientCodeGenerator::generate(),
            'dentist_id'      => $validated['dentist_id'] ?? null,
        ];

        return $this->createPatientUserAndProfile($userData, $patientData);
    }

     /**
     * Patient creation from an ActionRequest payload
     */
    public function createPatientFromActionRequest(
        int $currentUserId,
        ActionRequest $actionRequest
    ): Patient {
        $p = $actionRequest->payload;

        $userData = [
            'name'       => $p['name'],
            'username'   => UsernameGenerator::generatePatientUsername(),
            'phone'      => $p['phone'],
            'email'      => $p['email'],
            'password'   => Hash::make('password123'),
            'created_by' => $currentUserId,
        ];

        $patientData = [
            'date_of_birth'   => $p['date_of_birth'] ?? null,
            'gender'          => $p['gender'] ?? null,
            'medical_history' => $p['medical_history'] ?? null,
            'medical_notes'   => $p['medical_notes'] ?? null,
            'patient_code'    => PatientCodeGenerator::generate(),
            'dentist_id'      => $p['assigned_to_id'] ?? null,
        ];

        return $this->createPatientUserAndProfile($userData, $patientData);
    }


    public function createDentist($currentUserId, $validated)
    {

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
