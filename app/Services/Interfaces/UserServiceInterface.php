<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function getAllUsers(): Collection;
}