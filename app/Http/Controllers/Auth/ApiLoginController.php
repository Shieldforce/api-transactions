<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLogin\CallbackRequest;
use App\Http\Requests\ApiLogin\LoginRequest;
use App\Services\CallbackApiLoginService;
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
            'scope'         => '',
            'state'         => $state,
        ]);

        $baseApiAuth = env("API_AUTH_URL");

        return redirect("{$baseApiAuth}/oauth/authorize?{$query}");
    }

    public function callback(CallbackRequest $request)
    {
        try {
            $service = new CallbackApiLoginService();

            if ($service->runAuthorizationCode($request)) {
                return redirect()->route("dashboard");
            }

            throw new Exception("Erro ao validar autorização!");
        }
        catch (\Exception $exception) {
            return back()->with("error", $exception->getMessage());
        }
    }

    public function grantPassword(LoginRequest $request)
    {
        try {
            $service = new CallbackApiLoginService();

            if ($service->runPassword($request)) {
                return redirect()->route("dashboard");
            }

            throw new Exception("Erro ao validar autorização!");
        }
        catch (Exception $exception) {
            dd($exception);
            return back()->with("error", $exception->getMessage());
        }
    }

}
