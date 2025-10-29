<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return Helper::successResponse('Registration completed successfully', UserResource::make($result['user']), 201, $result['token']);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            return Helper::successResponse('Logged in successfully', UserResource::make($result['user']), 200, $result['token']);
        } catch (\Exception $e) {
            Helper::logException($e);

            return Helper::failResponse($e->getMessage(), 401);
        }

    }

    public function logout(Request $request): JsonResponse
    {
        $result = $this->authService->logout($request);

        return Helper::successResponse('Logged out successfully', UserResource::make($result), 200);
    }
}
