<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocialProviderController;
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
Route::delete('/user/{user}',[DashboardController::class,'delete_user'])->name('delete_user');
Route::post('/user/import',[DashboardController::class,'import_users'])->name('user.import');
Route::get('/clients',[DashboardController::class,'clients'])->name('clients');
Route::post('/clients',[DashboardController::class,'create_client'])->name('clients');
Route::get('/clients/{client}',[DashboardController::class,'show_client'])->name('show_client');
Route::patch('/clients/{client}',[DashboardController::class,'update_client'])->name('update_client');
Route::delete('/clients/{client}',[DashboardController::class,'destroy_client'])->name('clients.destroy');

Route::get('/authorized-clients',[DashboardController::class,'authorized_clients'])->name('authorized_clients');
Route::get('personal-access-tokens',[DashboardController::class,'personal_access_tokens'])->name('personal_access_tokens');

Route::get('/social-providers',[SocialProviderController::class,'index'])->name('social_providers');
Route::post('/social-providers',[SocialProviderController::class,'store'])->name('social_providers.store');
Route::patch('/social-providers/{provider}',[SocialProviderController::class,'update'])->name('social_providers.update');
Route::delete('/social-providers/{provider}',[SocialProviderController::class,'destroy'])->name('social_providers.destroy');

Route::get('/auth/{provider:driver}/redirect',[SocialProviderController::class,'redirect'])->name('social.auth.redirect');
Route::get('/auth/{provider:driver}/callback',[SocialProviderController::class,'callback'])->name('social.auth.callback');
Route::get('/auth/{provider:driver}/unlink',[SocialProviderController::class,'unlinkProvider'])->name('social.auth.unlink');
