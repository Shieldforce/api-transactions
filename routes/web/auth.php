<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::middleware('auth')->controller(ProfileController::class)->group(function () {

    Route::get('/profile', 'edit')
         ->name('profile.edit');

    Route::patch('/profile', 'update')
         ->name('profile.update');

    Route::delete('/profile', 'destroy')
         ->name('profile.destroy');

});

Route::get('auth/redirect', function (Request $request) {
    $state = Str::random(40);

    $query = http_build_query([
        'client_id'     => env("CLIENT_ID"),
        'redirect_uri'  => env("CLIENT_REDIRECT"),
        'response_type' => 'code',
        'scope'         => '',
        'state'         => $state,
        // 'prompt' => '', // "none", "consent", or "login"
    ]);

    $baseApiAuth = env("API_AUTH_URL");

    return redirect("{$baseApiAuth}/oauth/authorize?{$query}");
})->name("redirect");

Route::get('auth/callback', function (Request $request) {

    $state = $request->state;

    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class,
        'Invalid state value.'
    );

    $baseApiAuth = env("API_AUTH_URL");

    $response = Http::asForm()->post("{$baseApiAuth}/oauth/token", [
        'grant_type'    => 'authorization_code',
        'client_id'     => env("CLIENT_ID"),
        'client_secret' => env("CLIENT_SECRET"),
        'redirect_uri'  => env("CLIENT_REDIRECT"),
        'code'          => $request->code,
    ]);

    dd($response->json());

    return $response->json();
});
