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
    //Route::post('/auth/login',[ApiController::class,'login']);

    Route::post('/auth/logout', [ApiController::class, 'logout']);

    Route::get('/profile', [ApiController::class, 'profile']);
    Route::put('/profile/{user}', [ApiController::class, 'update_profile']);
    Route::patch('/profile/{user}', [ApiController::class, 'update_profile']);
    Route::put('/profile/password-update', [ApiController::class, 'update_password']);
    
    Route::middleware('auth:api')->get('/oauth/userinfo', function(){
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Build claims manually or load from claim services if needed
        return response()->json([
            'sub'   => $user->getKey(),
            'email' => $user->email,
            'name'  => $user->name,
            'preferred_username' => $user->email,
            'groups' => $user->groups->pluck('name')->toArray(),
            //'roles' => $user->roles->pluck('name')->toArray(), // adjust as needed
        ]);
    })->name('openid.userinfo');
});
