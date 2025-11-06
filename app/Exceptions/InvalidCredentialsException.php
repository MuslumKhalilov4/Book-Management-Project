<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    protected $message = 'Email or password is incorrect!';
}
