<?php

namespace App\Http\Requests\Transaction;

use App\Enums\PaymentTypeTransactionEnum;
use App\Enums\TypeTransactionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "user_id" => Auth::id()
        ]);
    }

    public function rules(): array
    {
        return [
            "user_id"      => ["required", Rule::exists("users", "id")],
            "description"  => ["required", "string"],
            "value"        => ["required"],
            "due_date"     => ["required", "date"],
            "payment_date" => ["nullable", "date"],
            "type"         => ["required", Rule::enum(TypeTransactionEnum::class)],
            "type_payment" => ["required", Rule::enum(PaymentTypeTransactionEnum::class)],
        ];
    }
}
