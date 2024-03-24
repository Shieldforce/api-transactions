<?php

namespace App\Services;

use App\Http\Requests\ApiLogin\CallbackRequest;
use App\Http\Requests\ApiLogin\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class CallbackApiLoginService
{
    public function runAuthorizationCode(CallbackRequest $request)
    {
        $data         = $request->validated();
        $baseApiAuth  = env("API_AUTH_URL");
        $response     = $this->authorizationResponse($baseApiAuth, $data);
        $responseUser = $this->userResponse($baseApiAuth, $response);
        $this->authUser($this->userSave($responseUser));

        if (Auth::check()) {
            #TODO: implements scopes (ACL)

            return redirect()->route("dashboard");
        }

        throw new Exception("Erro ao redirecionar para Dashboard!");
    }

    public function runPassword(LoginRequest $request)
    {
        $data         = $request->validated();
        $response     = $this->authorizationResponsePassword($data);
        $responseUser = $this->userResponse($data['base_url'], $response);
        $this->authUser($this->userSave($responseUser));

        if (Auth::check()) {
            #TODO: implements scopes (ACL)

            return redirect()->route("dashboard");
        }

        throw new Exception("Erro ao redirecionar para Dashboard!");
    }

    private function authorizationResponse($baseApiAuth, $data): Response
    {
        return Http::asForm()->post("{$baseApiAuth}/oauth/token", [
            'grant_type'    => "authorization_code",
            'client_id'     => env("CLIENT_ID"),
            'client_secret' => env("CLIENT_SECRET"),
            'redirect_uri'  => env("CLIENT_REDIRECT"),
            'code'          => $data["code"],
        ]);
    }

    private function authorizationResponsePassword($data)
    {
        return Http::asForm()->post("{$data['base_url']}/oauth/token", [
            'grant_type'    => 'password',
            'client_id'     => $data['client_id'],
            'client_secret' => $data['client_secret'],
            'username'      => $data['username'],
            'password'      => $data['password'],
            'scope'         => '',
        ]);
    }

    private function userResponse($baseApiAuth, $response): Response
    {
        $token_type   = $response->json()['token_type'];
        $access_token = $response->json()['access_token'];
        return Http::withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => "{$token_type} {$access_token}",
        ])->get("{$baseApiAuth}/api/user");
    }

    private function userSave($responseUser): User
    {
        return User::updateOrCreate([
            'name'  => $responseUser["name"],
            'email' => $responseUser["email"],
        ], [
            'password' => bcrypt(Crypt::encrypt($responseUser)),
        ]);
    }

    private function authUser(User $user): void
    {
        Auth::login($user);
    }
}
