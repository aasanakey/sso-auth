<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/users',[DashboardController::class,'users'])->name('users');
Route::post('/user',[DashboardController::class,'store_user'])->name('user');
Route::get('/user/{user}',[DashboardController::class,'show_user'])->name('show_user');
Route::patch('/user/{user}',[DashboardController::class,'update_user'])->name('update_user');
Route::get('/clients',[DashboardController::class,'clients'])->name('clients');
Route::post('/clients',[DashboardController::class,'create_client'])->name('clients');
Route::get('/clients/{client}',[DashboardController::class,'clients'])->name('clients');
Route::delete('/clients/{client}',[DashboardController::class,'destroy_client'])->name('clients.destroy');