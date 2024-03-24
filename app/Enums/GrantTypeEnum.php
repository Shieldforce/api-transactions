<?php

namespace App\Enums;

enum GrantTypeEnum: string
{
    case TYPE_1 = "authorization_code";
    case TYPE_2 = "password";
    case TYPE_3 = "client_credentials";
}
