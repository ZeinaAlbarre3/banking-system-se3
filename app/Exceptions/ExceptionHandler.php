<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Throwable;

class ExceptionHandler
{
    use ResponseTrait ;

    public static function handle(Throwable $e)
    {
        $handlers = config('exceptions');

        foreach ($handlers as $exceptionClass => $handlerFunction) {
            if ($e instanceof $exceptionClass) {
                if (function_exists($handlerFunction)) {
                    return call_user_func($handlerFunction, $e);
                }
            }
        }
        return self::Error([], $e->getMessage() ?: 'Something went wrong.', 500);
    }
}
