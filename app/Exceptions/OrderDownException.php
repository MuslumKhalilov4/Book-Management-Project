<?php

namespace App\Exceptions;

use Exception;

class OrderDownException extends BaseException
{
    public function __construct($modelName)
    { 
        parent::__construct($modelName . ' is already at the bottom position', 409);
    }
}
