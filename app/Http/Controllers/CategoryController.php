<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\Interfaces\SortOrderServiceInterface;
use Illuminate\Http\JsonResponse;


class CategoryController extends Controller
{
    protected $categoryService;
    protected $sortOrderService;

    public function __construct(CategoryServiceInterface $categoryService, SortOrderServiceInterface $sortOrderService)
    {
        $this->categoryService = $categoryService;
        $this->sortOrderService = $sortOrderService;
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


    public function softDelete($id): JsonResponse
    {
        $category = $this->categoryService->softDelete($id);

        return Helper::successResponse('Category deleted successfully!', CategoryResource::make($category), 200);
    }

    public function forceDelete($id): JsonResponse
    {
        $category = $this->categoryService->forceDelete($id);

        return Helper::successResponse('Category permanently deleted successfully!', CategoryResource::make($category), 200);
    }

    public function orderUp($id): JsonResponse
    {
        try {
            $category = $this->sortOrderService->orderUp($id, new Category());

            return Helper::successResponse('Category successfully moved up!', CategoryResource::make($category), 200);
        } catch (\Exception $e) {
            return Helper::failResponse($e->getMessage(), 400);
        }
    }

    public function orderDown($id): JsonResponse
    {
        try {
            $category = $this->sortOrderService->orderDown($id, new Category());

            return Helper::successResponse('Category successfully moved down!', CategoryResource::make($category), 200);
        } catch (\Exception $e) {
            return Helper::failResponse($e->getMessage(), 400);
        }
    }
}
