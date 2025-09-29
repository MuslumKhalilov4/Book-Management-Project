<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Services\Interfaces\AuthorServiceInterface;
use App\Services\Interfaces\SortOrderServiceInterface;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    protected $authorService;
    protected $sortOrderService;

    public function __construct(AuthorServiceInterface $authorService, SortOrderServiceInterface $sortOrderService)
    {
        $this->authorService = $authorService;
        $this->sortOrderService = $sortOrderService;
    }

    public function index(): JsonResponse
    {
        $authors = $this->authorService->getAllAuthors();

        return Helper::successResponse('Authors retrieved successfully!', AuthorResource::collection($authors), 200);
    }

    public function show($id): JsonResponse
    {
        $author = $this->authorService->getSingleAuthor($id);

        return Helper::successResponse('Author retrieved successfully!', AuthorResource::make($author), 200);
    }

    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = $this->authorService->store($request->validated());

        return Helper::successResponse('Author created successfully!', AuthorResource::make($author), 201);
    }

    public function update($id, UpdateAuthorRequest $request): JsonResponse
    {
        $author = $this->authorService->update($id, $request->validated());

        return Helper::successResponse('Author updated successfully!', AuthorResource::make($author), 200);
    }


    public function softDelete($id): JsonResponse
    {
        $author = $this->authorService->softDelete($id);

        return Helper::successResponse('Author deleted successfully!', AuthorResource::make($author), 200);
    }

    public function forceDelete($id): JsonResponse
    {
        $author = $this->authorService->forceDelete($id);

        return Helper::successResponse('Author permanently deleted successfully!', AuthorResource::make($author), 200);
    }

    public function orderUp($id): JsonResponse
    {
        try {
            $author = $this->sortOrderService->orderUp($id, new Author());

            return Helper::successResponse('Author successfully moved up!', AuthorResource::make($author), 200);
        } catch (\Exception $e) {
            return Helper::failResponse($e->getMessage(), 400);
        }
    }

    public function orderDown($id): JsonResponse
    {
        try {
            $author = $this->sortOrderService->orderDown($id, new Author());

            return Helper::successResponse('Author successfully moved down!', AuthorResource::make($author), 200);
        } catch (\Exception $e) {
            return Helper::failResponse($e->getMessage(), 400);
        }
    }
}
