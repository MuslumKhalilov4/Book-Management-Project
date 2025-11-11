<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function getAllUsers();

    public function getSingleUser($id): User;

    public function makeAdmin($id): User;

    public function removeAdmin($id): User;

    public function myProfile(): User;

    public function updateMyProfile(array $request):User;
}