<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PatientResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    // Route::get('/profile', [PatientController::class, 'getPatientProfile']);
    public function getPatientProfile(Request $request)  {
        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

    // Route::post('/profile', [PatientController::class, 'updatePatientProfile']);
    public function updatePatientProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'medical_history' => 'nullable|string',
        ]);

        DB::transaction(function () use ($user, $validated) {
            // Update user table
            $user->update(Arr::only($validated, ['name', 'email', 'phone']));

            // Update or create patient record
            $user->patient()->updateOrCreate(
                ['user_id' => $user->id],
                Arr::only($validated, ['birth_date', 'gender', 'address', 'medical_history'])
            );
        });

        return apiResponse(
            data: new PatientResource($user->fresh('patient')),
            message: 'Profile updated successfully'
        );
    }


    // Route::get('/appointments', [PatientController::class, 'getPatientAppointments']);
    public function getPatientAppointments(Request $request)  {
        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

}
