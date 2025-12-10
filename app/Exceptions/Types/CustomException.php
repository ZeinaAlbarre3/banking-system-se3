<?php

namespace App\Exceptions\Types;

use Exception;

class CustomException extends Exception
{
    protected $code;

    public function __construct($message,$code = 400)
    {
        parent::__construct($message);
        $this->code = $code;
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }
}
