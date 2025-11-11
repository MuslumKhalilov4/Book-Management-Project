<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyAdminException extends BaseException
{
    public function __construct()
    {
        parent::__construct("User's role is already admin!", 409);
    }
}
