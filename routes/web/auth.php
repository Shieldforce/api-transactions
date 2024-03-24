<?php

use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->controller(ProfileController::class)
    ->group(function () {

    Route::get('/profile', 'edit')
         ->name('profile.edit');

    Route::patch('/profile', 'update')
         ->name('profile.update');

    Route::delete('/profile', 'destroy')
         ->name('profile.destroy');

});


Route::controller(ApiLoginController:: class)->group(function () {

    Route::get('auth/redirect',"redirect")->name("redirect");

    Route::get('auth/callback', "callback")->name("callback");

});

