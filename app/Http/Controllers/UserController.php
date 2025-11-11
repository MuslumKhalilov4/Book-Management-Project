<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return Helper::successResponse('Users retrieved successfully!', UserResource::collection($users), 200);
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->getSingleUser($id);

        return Helper::successResponse('User retrieved successfully!', UserResource::make($user), 200);
    }

    public function makeAdmin($id): JsonResponse
    {
        $user = $this->userService->makeAdmin($id);

        return Helper::successResponse('User role changed to admin!', UserResource::make($user), 200);
    }

    public function removeAdmin($id): JsonResponse
    {
        $user = $this->userService->removeAdmin($id);

        return Helper::successResponse('User role changed to user!', UserResource::make($user), 200);
    }

    public function myProfile(): JsonResponse
    {
        $user = $this->userService->myProfile();

        return Helper::successResponse('Profile found successfully!', UserResource::make($user), 200);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->userService->updateMyProfile($request->validated());

        return Helper::successResponse('Your profile updated successfully!', UserResource::make($user), 200);
    }
}
