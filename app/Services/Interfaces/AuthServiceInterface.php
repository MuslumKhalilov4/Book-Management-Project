<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function register($request): array; 

    public function login($request): array;

    public function logout($request): User;
}