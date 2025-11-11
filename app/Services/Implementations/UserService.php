<?php

namespace App\Services\Implementations;

use App\Exceptions\UserAlreadyAdminException;
use App\Exceptions\UserAlreadyUserException;
use App\Models\Role;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements UserServiceInterface
{
    public function getAllUsers()
    {
        $users = User::with('role')->paginate(10);

        if ($users->isEmpty()) {
            throw new NotFoundHttpException();
        }

        return $users;
    }

    public function getSingleUser($id): User
    {
        $user = User::with('role')->findOrFail($id);

        return $user;
    }

    public function makeAdmin($id): User
    {
        $user = User::findOrFail($id);
        $role_id = Role::where('title', 'admin')->first()->id;

        if ($user->role->id == $role_id) {
            throw new UserAlreadyAdminException();
        }

        $user->update(['role_id' => $role_id]);

        return $user->fresh();
    }

    public function removeAdmin($id): User
    {
        $user = User::findOrFail($id);
        $role_id = Role::where('title', 'user')->first()->id;

        if ($user->role->id == $role_id) {
            throw new UserAlreadyUserException();
        }

        $user->update(['role_id' => $role_id]);

        return $user->fresh();
    }

    public function myProfile(): User
    {
        $user = auth()->user();

        return $user;
    }

    public function updateMyProfile(array $request): User
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->update($request);

        return $user->fresh();
    }
}
