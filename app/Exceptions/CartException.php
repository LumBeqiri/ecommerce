<?php

namespace App\Exceptions;

use Exception;

class CartException extends Exception
{
    public function __construct(string $message = 'Cart functionality failed', int $code = 400, ?Exception $previous = null) 
    {
        parent::__construct($message, $code, $previous);
    }
}
