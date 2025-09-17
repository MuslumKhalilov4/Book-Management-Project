<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;


class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();

        return Helper::successResponse('Categories retrieved successfully!', CategoryResource::collection($categories), 200);
    }

    public function show($id): JsonResponse
    {
        $category = $this->categoryService->getSingleCategory($id);

        return Helper::successResponse('Category retrieved successfully!', CategoryResource::make($category), 200);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->store($request->validated());

        return Helper::successResponse('Category created successfully!', CategoryResource::make($category), 201);
    }

    public function update($id, UpdateCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->update($id, $request->validated());

        return Helper::successResponse('Category updated successfully!', CategoryResource::make($category), 200);
    }


    public function destroy($id): JsonResponse
    {
        $category = $this->categoryService->destroy($id);

        return Helper::successResponse('Category deleted successfully!', CategoryResource::make($category), 200);
    }
}
