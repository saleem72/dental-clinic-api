<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RolesStrings;
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

class ReceptionistsController extends Controller
{
    public function index()
    {

        // $receptionists = User::whereHas('roles', function ($q) {
        //     // --- THIS IS THE LINE IN QUESTION ---
        //     $q->where('name', RolesStrings::RECEPTIONIST);
        //     // ------------------------------------
        // })->get();


        $receptionists = User::whereHas('roles', function ($q) {
            $q->where('name', RolesStrings::RECEPTIONIST);
        })->get();

        return apiResponse(
            data: UserResource::collection($receptionists),
            success: true,
            message: 'Receptionists list retrieved successfully.'
        );
    }

    public function createReceptionist()  {
        $data = [
            "name" => 'Maya',
            "username" => 'maya',
            'password' => Hash::make('password123'),
        ];

        $user = User::create($data);
        $patientRole = Role::where('name', RolesStrings::RECEPTIONIST)->firstOrFail();
        $user->roles()->sync([$patientRole->id]);

        return apiResponse(status: 201, data: new UserResource($user));
    }
}
