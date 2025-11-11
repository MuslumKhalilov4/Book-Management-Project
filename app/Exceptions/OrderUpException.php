<?php

namespace App\Exceptions;

use Exception;

class OrderUpException extends BaseException
{
    public function __construct($modelName)
    {
        parent::__construct($modelName . ' is already at the top position', 409);
    }
}
