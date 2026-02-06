<?php

namespace App\Exceptions;

use Exception;

class CurrentPasswordCheckException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Password is incorrect!', 401);
    }
}
