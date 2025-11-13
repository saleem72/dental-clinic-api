<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DentistController;
use App\Http\Controllers\Api\V1\DentistsController;
use App\Http\Controllers\Api\V1\PatientController;
use App\Http\Controllers\Api\V1\PatientsController;
use App\Http\Controllers\Api\V1\ReceptionistController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UsersController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Route;

Route::prefix('exceptions')->group(function () {
    Route::get('/un-auth', fn() => throw new AuthenticationException());
    Route::get('/forbidden', fn() => abort(403));
    Route::get('/not-found', fn() => abort(404));
    Route::post('/validate', fn() => request()->validate(['name' => 'required', 'password' => 'required']));
    Route::get('/server-error', function () {
        throw new Exception('Test server exception');
    });
});


Route::post('/auth/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function() {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
});

Route::middleware(['auth:sanctum', 'valid_password'])->group(function () {

    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/profile', 'getProfile');
        Route::put('/profile', 'updateProfile');
    });

    Route::prefix('users')
        ->middleware('role:manager')
        ->controller(UsersController::class)
        ->group(function() {
            Route::get('/', 'index');
            Route::get('/roles', 'roles');
            Route::post('/{user}/toggle-activity', 'toggleUserActivity');
            Route::post('/{user}/reset-password', 'resetUserPassword');
            Route::post('/set-default-avatar', 'setDefaultAvatar');
            Route::get('/by-id/{user}', 'userById');
            Route::get('/by-username/{username}', 'userByUsername');
        });

    Route::prefix('patients')
        ->controller(PatientsController::class)
        ->group(function() {
            Route::get('/', 'index')->middleware('role.any:manager|dentist|receptionist');
            Route::post('/', 'createPatient')->middleware('role.any:manager|dentist|receptionist');
            Route::get('/search', 'searchPatients');
        });

    Route::prefix('dentists')
        ->controller(DentistsController::class)
        ->group(function() {
            Route::get('/', 'index')->middleware('role.any:manager|dentist|receptionist');
            Route::post('/', 'createDentist')->middleware('role.any:manager|dentist|receptionist');
            Route::get('/search', 'searchDentists');
        });

    Route::prefix('patient')
        ->middleware('role:patient')
        ->controller(PatientController::class)
        ->group(function () {
            Route::get('/profile', 'getPatientProfile');
            Route::put('/profile', 'updatePatientProfile');
            Route::get('/appointments', 'getPatientAppointments');
        });

    Route::prefix('dentist')
        ->middleware('role:dentist')
        ->controller(DentistController::class)
        ->group(function () {
            Route::get('/profile', 'getDentistProfile');
            Route::put('/profile', 'updateDentistProfile');
            Route::get('/patients', 'getDentistPatients');
            Route::get('/schedule', 'getDentistSchedule');
        });
});
