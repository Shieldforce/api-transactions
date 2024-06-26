<?php

namespace App\Services;

use App\Http\Requests\ApiLogin\GrantAuthorizationCodeRequest;
use App\Http\Requests\ApiLogin\GrantClientCredentialsRequest;
use App\Http\Requests\ApiLogin\GrantPasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class CallbackApiLoginService
{
    public function runAuthorizationCode(GrantAuthorizationCodeRequest $request)
    {
        $data         = $request->validated();
        $baseApiAuth  = env("API_AUTH_URL");
        $response     = $this->authorizationResponse($baseApiAuth, $data);
        $responseUser = $this->userResponse($baseApiAuth, $response);
        $user         = $this->userSave($responseUser);
        $this->authUser($user);

        if (Auth::check()) {
            $this->abilitiesSave($this->listScopes($response));

            return redirect()->route("/dashboard");
        }

        throw new Exception("Erro ao redirecionar para Dashboard!");
    }

    public function runPassword(GrantPasswordRequest $request)
    {
        $data         = $request->validated();
        $response     = $this->authorizationResponsePassword($data);
        $responseUser = $this->userResponse($data['base_url'], $response);
        $user         = $this->userSave($responseUser);
        $this->authUser($user);

        if (Auth::check()) {
            $this->abilitiesSave($this->listScopes($response));

            return redirect()->route("/dashboard");
        }

        throw new Exception("Erro ao redirecionar para Dashboard!");
    }

    public function runClientCredentials(GrantClientCredentialsRequest $request)
    {
        $data     = $request->validated();
        $response = $this->authorizationResponseClientCredentials($data);

        if (isset($response->json()["access_token"])) {
            return array_merge($response->json(), ["scopes" => $this->listScopes($response)]);
        }

        throw new Exception("Erro ao fazer login como cliente!");
    }

    private function authorizationResponse($baseApiAuth, $data): Response
    {
        return Http::asForm()->post("{$baseApiAuth}/oauth/token", [
            'grant_type'    => "authorization_code",
            'client_id'     => env("CLIENT_ID"),
            'client_secret' => env("CLIENT_SECRET"),
            'redirect_uri'  => env("CLIENT_REDIRECT"),
            'code'          => $data["code"],
            'scope'         => ListScopesPassportService::getList()
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
            'scope'         => ListScopesPassportService::getList(),
        ]);
    }

    private function authorizationResponseClientCredentials($data)
    {
        return Http::asForm()->post("{$data['base_url']}/oauth/token", [
            'grant_type'    => 'client_credentials',
            'client_id'     => $data['client_id'],
            'client_secret' => $data['client_secret'],
            'scope'         => ListScopesPassportService::getList(),
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

    private function abilitiesSave(array $listScopes)
    {
        $user = request()->user();

        $user->tokens()->delete();

        $user->createToken('integration', array_values($listScopes));
    }

    private function authUser(User $user): void
    {
        Auth::login($user);
    }

    private function listScopes($response): array
    {
        $accessToken  = $response->json()["access_token"];
        $tokenPayload = explode('.', $accessToken)[1];
        $tokenPayload = base64_decode($tokenPayload);
        $tokenData    = json_decode($tokenPayload, true);
        return $tokenData['scopes'];
    }
}
