<?php

namespace App\Enums;

enum PaymentTypeTransactionEnum: int
{
    case boleto = 1;
    case cartao = 2;
    case pix    = 3;
}
