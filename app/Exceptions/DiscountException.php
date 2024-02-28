<?php

namespace App\Exceptions;

use Exception;

class DiscountException extends Exception
{
    public function __construct($message = 'Invalid discount', $code = 422, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
