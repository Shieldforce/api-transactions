<?php

namespace App\Http\Requests\Transaction;

use App\Enums\PaymentTypeTransactionEnum;
use App\Enums\TypeTransactionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "description"  => ["nullable", "string"],
            "value"        => ["nullable"],
            "due_date"     => ["nullable", "date"],
            "payment_date" => ["nullable", "date"],
            "type"         => ["nullable", Rule::enum(TypeTransactionEnum::class)],
            "type_payment" => ["nullable", Rule::enum(PaymentTypeTransactionEnum::class)],
        ];
    }
}
