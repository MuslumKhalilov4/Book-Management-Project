<?php

namespace App\Exceptions;

use Exception;

class OrderDownException extends Exception
{
    protected $message;

    public function __construct($modelName)
    {
        $this->message = $modelName . ' is already at the bottom position';
    }
}
