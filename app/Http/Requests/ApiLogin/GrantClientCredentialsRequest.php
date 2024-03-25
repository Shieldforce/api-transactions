<?php

namespace App\Http\Requests\ApiLogin;

use Illuminate\Foundation\Http\FormRequest;

class GrantClientCredentialsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'     => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'base_url'      => ['required', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "client_id"     => env("CLIENT_GTCC_ID") ?? null,
            "client_secret" => env("CLIENT_GTCC_SECRECT") ?? null,
            "base_url"      => env("API_AUTH_URL") ?? null,
        ]);
    }
}
