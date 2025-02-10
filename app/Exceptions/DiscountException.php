<?php

namespace App\Exceptions;

use Exception;

class DiscountException extends Exception
{
    public function __construct(string $message = 'Invalid discount', int $code = 422, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
