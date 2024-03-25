<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLogin\GrantAuthorizationCodeRequest;
use App\Http\Requests\ApiLogin\GrantClientCredentialsRequest;
use App\Http\Requests\ApiLogin\GrantPasswordRequest;
use App\Services\CallbackApiLoginService;
use App\Services\ListScopesPassportService;
use Exception;
use Illuminate\Support\Str;

class ApiLoginController extends Controller
{
    public function redirect()
    {
        $state = Str::random(40);

        $query = http_build_query([
            'client_id'     => env("CLIENT_ID"),
            'redirect_uri'  => env("CLIENT_REDIRECT"),
            'response_type' => 'code',
            'scope'         => ListScopesPassportService::getList(),
            'state'         => $state,
        ]);

        $baseApiAuth = env("API_AUTH_URL");

        return redirect("{$baseApiAuth}/oauth/authorize?{$query}");
    }

    public function callback(GrantAuthorizationCodeRequest $request)
    {
        try {
            $service = new CallbackApiLoginService();

            if ($service->runAuthorizationCode($request)) {
                return redirect()->route("dashboard");
            }

            throw new Exception("Erro ao validar autorização!");
        }
        catch (\Exception $exception) {
            return redirect()->route("login")
                             ->with("error", $exception->getMessage());
        }
    }

    public function grantPassword(GrantPasswordRequest $request)
    {
        try {
            $service = new CallbackApiLoginService();

            if ($service->runPassword($request)) {
                return redirect()->route("dashboard");
            }

            throw new Exception("Erro ao validar autorização!");
        }
        catch (Exception $exception) {
            return redirect()->route("login")
                             ->with("error", $exception->getMessage());
        }
    }

    public function grantClientCredentials(GrantClientCredentialsRequest $request)
    {
        try {
            $service = new CallbackApiLoginService();

            return response()->json($service->runClientCredentials($request));
        }
        catch (Exception $exception) {
            return response()->json($exception);
        }
    }

}
