<?php

namespace App\Http\Requests\ApiLogin;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'         => ['required', 'string', 'email'],
            'password'      => ['required', 'string'],
            'username'      => ['required', 'string'],
            'client_id'     => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'base_url'      => ['required', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "username"      => $this->email ?? null,
            "client_id"     => env("CLIENT_GTP_ID") ?? null,
            "client_secret" => env("CLIENT_GTP_SECRECT") ?? null,
            "base_url"      => env("API_AUTH_URL") ?? null,
        ]);
    }
}
