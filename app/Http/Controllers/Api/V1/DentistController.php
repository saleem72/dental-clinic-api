<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    // Route::get('/profile', [DentistController::class, 'getDentistProfile']);
    public function getDentistProfile(Request $request)  {
        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

    // Route::post('/profile', [DentistController::class, 'updateDentistProfile']);
    public function updateDentistProfile(Request $request)  {
        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

    // Route::get('/patients', [DentistController::class, 'getDentistPatients']);
    public function getDentistPatients(Request $request)  {
        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

    // Route::get('/schedule', [DentistController::class, 'getDentistSchedule']);
    public function getDentistSchedule(Request $request)  {
        return apiResponse(
            status: 400,
            success: false,
            message: 'you have to implement this',
        );
    }

}
