<?php

namespace App\Http\Controllers;

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

        return $this->successResponse('Users retrieved successfully!', UserResource::collection($users), 200);
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->getSingleUser($id);

        return $this->successResponse('User retrieved successfully!', UserResource::make($user), 200);
    }

    public function makeAdmin($id): JsonResponse
    {
        $user = $this->userService->makeAdmin($id);

        return $this->successResponse('User role changed to admin!', UserResource::make($user), 200);
    }

    public function removeAdmin($id): JsonResponse
    {
        $user = $this->userService->removeAdmin($id);

        return $this->successResponse('User role changed to user!', UserResource::make($user), 200);
    }

    public function myProfile(): JsonResponse
    {
        $user = $this->userService->myProfile();

        return $this->successResponse('Profile found successfully!', UserResource::make($user), 200);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->userService->updateMyProfile($request->validated());

        return $this->successResponse('Your profile updated successfully!', UserResource::make($user), 200);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['string', 'required'],
            'new_password' => ['string', 'required']
        ]);

        $user = $this->userService->resetPassword($request->all());

        return $this->successResponse('Password changed successfully!', UserResource::make($user), 200);
    }
}
