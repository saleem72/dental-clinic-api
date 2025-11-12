<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome to BlueClinic Api';
});


Route::prefix('v1')->group(function () {
    require base_path('routes/Api/V1/api.php');
});
