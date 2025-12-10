<?php

namespace App\Domains\Auth\Enums;

enum OtpActionTypeEnum: string
{
    case REGISTER = 'register';
    case RESET = 'reset';
}
