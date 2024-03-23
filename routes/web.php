<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

foreach (File::allFiles(__DIR__ . '/web') as $route_file) {
    require $route_file->getPathname();
}

require __DIR__.'/auth.php';
