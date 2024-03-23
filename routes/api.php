<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

foreach (File::allFiles(__DIR__ . '/api') as $route_file) {
    require $route_file->getPathname();
}
