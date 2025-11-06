<?php

namespace App\Exceptions;

use Exception;

class OrderUpException extends Exception
{
    protected $message;

    public function __construct($modelName)
    {
        $this->message = $modelName . ' is already at the top position';
    }
}
