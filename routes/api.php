<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware(['client'])->group(function () {
    Route::post('/auth/register', [ApiController::class, 'register']);
    Route::post('/auth/forgot-password', [ApiController::class, 'forgot_password']);
    Route::post('/auth/change-password', [ApiController::class, 'change_password']);
});

Route::middleware(['auth:api'])->group(function () {
    //Route::post('/api/auth/login',[ApiController::class,'login']);

    Route::post('/auth/logout', [ApiController::class, 'logout']);

    Route::get('/profile', [ApiController::class, 'profile']);
    Route::patch('/profile/{user}', [ApiController::class, 'update_profile']);
    Route::patch('/profile/{user}', [ApiController::class, 'update_profile']);
});
