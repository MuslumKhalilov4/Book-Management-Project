<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Email or password is incorrect!', 401);
    }
}
