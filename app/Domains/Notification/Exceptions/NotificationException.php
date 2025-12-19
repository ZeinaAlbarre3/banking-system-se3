<?php

namespace App\Domains\Notification\Exceptions;

use App\Exceptions\Types\CustomException;

class NotificationException extends CustomException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 422);
    }
}
