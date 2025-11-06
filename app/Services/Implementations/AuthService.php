<?php

namespace App\Services\Implementations;

use App\Exceptions\InvalidCredentialsException;
use App\Helpers\Helper;
use App\Models\Role;
use App\Models\User;
use App\Services\Interfaces\AuthServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function register($request): array
    {
        DB::beginTransaction();

        $role_id = Role::where('title', 'user')->first()->id;

        try {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'role_id' => $role_id
            ]);

            if ($user) {
                $token = $user->createToken('auth_token')->plainTextToken;
            }

            DB::commit();

            return [
                'token' => $token,
                'user' => $user
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function login($request): array
    {
        DB::beginTransaction();

        try {
            $user = User::where('email', $request['email'])->first();

            if (!$user || !Hash::check($request['password'], $user->password)) {
                throw new InvalidCredentialsException();
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return [
                'user' => $user,
                'token' => $token
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function logout($request): User
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return $user;
    }
}
