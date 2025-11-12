<?php

use App\Http\Controllers\Api\V1\AuthController;
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

Route::middleware(['auth:sanctum', 'valid_password'])->group(function() {
    Route::prefix('user')->group(function() {
        Route::get('/profile', [UserController::class, 'getProfile']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
    });

    Route::prefix('users')->middleware('role:manager') ->group(function() {
        Route::get('/', [UsersController::class, 'index']);
        Route::get('/roles', [UsersController::class, 'roles']);
        Route::post('/create-user', [UsersController::class, 'createUser']);
        Route::post('/toggle-user-activity/{user}', [UsersController::class, 'toggleUserActivity']);
        Route::post('/reset-user-password/{user}', [UsersController::class, 'resetUserPassword']);
        Route::post('/set-default-avatar', [UsersController::class, 'setDefaultAvatar']);
    });
});
