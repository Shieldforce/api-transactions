<?php

namespace App\Http\Resources;

use App\Enums\PaymentTypeTransactionEnum;
use App\Enums\TypeTransactionEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id"                => $this->id,
            "user"              => $this->user,
            "description"       => $this->description,
            "value"             => $this->value,
            "due_date"          => Carbon::create($this->due_date)->format("d/m/Y"),
            "payment_date"      => $this->payment_date ?
                Carbon::create($this->payment_date)->format("d/m/Y") :
                null,
            "type"              => $this->type,
            "type_payment"      => $this->type_payment,
            "type_text"         => TypeTransactionEnum::from($this->type)->name,
            "type_payment_text" => PaymentTypeTransactionEnum::from($this->type_payment)->name,
        ];
    }
}
