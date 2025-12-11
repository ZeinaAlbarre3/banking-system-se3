<?php

namespace App\Domains\Transaction\Exceptions;

use App\Exceptions\Types\CustomException;

class TransactionRuleException extends CustomException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 422);
    }
}
