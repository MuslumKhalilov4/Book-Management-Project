<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct($message, $statusCode)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(){
        return $this->statusCode;
    }
}
