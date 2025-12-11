<?php

namespace App\Domains\Account\Exceptions;

use App\Exceptions\Types\CustomException;

class AccountStateException extends CustomException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 422);
    }
}
