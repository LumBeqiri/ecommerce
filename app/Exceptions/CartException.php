<?php 
namespace App\Exceptions;

use Exception;

class CartException extends Exception
{
    public function __construct($message = 'Cart functionality failed', $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}