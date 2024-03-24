<?php

namespace App\Services;

use App\Http\Requests\ApiLogin\CallbackRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class CallbackApiLoginService
{
    public function run(CallbackRequest $request)
    {
        $data        = $request->validated();
        $baseApiAuth = env("API_AUTH_URL");

        $response = Http::asForm()->post("{$baseApiAuth}/oauth/token", [
            'grant_type'    => "authorization_code",
            'client_id'     => env("CLIENT_ID"),
            'client_secret' => env("CLIENT_SECRET"),
            'redirect_uri'  => env("CLIENT_REDIRECT"),
            'code'          => $data["code"],
        ]);

        $token_type   = $response->json()['token_type'];
        $access_token = $response->json()['access_token'];

        $responseUser = Http::withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => "{$token_type} {$access_token}",
        ])->get("{$baseApiAuth}/api/user");

        $encryptUser = Crypt::encrypt($responseUser);

        $user = User::updateOrCreate([
            'name'  => $responseUser["name"],
            'email' => $responseUser["email"],
        ], [
            'password' => bcrypt($encryptUser),
        ]);

        Auth::login($user);

        if (Auth::check()) {
            #TODO: implements scopes (ACL)

            return redirect()->route("dashboard");
        }

    }
}
