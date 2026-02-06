<?php

namespace App\Exceptions;

use Exception;

class AlreadyRatedException extends BaseException
{
    public function __construct()
    {
        parent::__construct('You already rated this book', 409);
    }
}
