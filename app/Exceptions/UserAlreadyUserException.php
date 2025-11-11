<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyUserException extends Exception
{
    public function __construct()
    {
        parent::__construct("User's role is already user!", 409);
    }
}
