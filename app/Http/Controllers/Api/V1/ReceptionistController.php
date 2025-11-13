<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreatePatientRequest;
use App\Http\Resources\V1\PatientResource;
use App\Http\Resources\V1\UserMiniResource;
use App\Http\Resources\V1\UserResource;
use App\Models\V1\Role;
use App\Models\V1\User;
use App\Services\CreatePatientService;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReceptionistController extends Controller
{
    // Route::get('/doctors', [ReceptionistController::class, 'getDoctors']);
    public function getDoctors(Request $request)  {
        // Get all users who have the "dentist" role
        $query = User::query()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'dentist');
            })
            ->with(['dentist']); // eager load dentist info if exists

        // Optional filters (availability or specialization only if dentist record exists)
        if ($request->filled('available')) {
            $query->whereHas('dentist', function ($q) use ($request) {
                $q->where('is_available', (bool) $request->boolean('available'));
            });
        }

        if ($request->filled('specialization')) {
            $query->whereHas('dentist', function ($q) use ($request) {
                $q->where('specialization', 'like', '%' . $request->specialization . '%');
            });
        }

        $doctors = $query->get();

        return apiResponse(
            data: UserMiniResource::collection($doctors),
            success: true,
            message: 'Doctors list retrieved successfully.'
        );
    }

    // Route::get('/doctors', [ReceptionistController::class, 'getDoctors']);
    public function getPatients(Request $request)  {
        // Get all users who have the "dentist" role
        $query = User::query()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'patient');
            })
            ->with(['patient']); // eager load dentist info if exists

        // Optional filters (availability or specialization only if dentist record exists)
        // if ($request->filled('available')) {
        //     $query->whereHas('dentist', function ($q) use ($request) {
        //         $q->where('is_available', (bool) $request->boolean('available'));
        //     });
        // }

        // if ($request->filled('specialization')) {
        //     $query->whereHas('dentist', function ($q) use ($request) {
        //         $q->where('specialization', 'like', '%' . $request->specialization . '%');
        //     });
        // }

        $patients =  $query->get()->pluck('patient')->filter();


        return apiResponse(
            data: PatientResource::collection($patients),
            success: true,
            message: 'Doctors list retrieved successfully.'
        );
    }







    // Route::get('/patients/search', [ReceptionistController::class, 'searchPatients']);
    public function searchPatients(Request $request)  {


        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

    // Route::post('/appointments', [ReceptionistController::class, 'setAppointment']);
    public function setAppointment(Request $request)  {
        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

    // Route::get('/appointments/{dentist}', [ReceptionistController::class, 'getDoctorAppointments']);
    public function getDoctorAppointments(Request $request)  {
        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

}
