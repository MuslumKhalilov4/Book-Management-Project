<?php

namespace App\Services\Implementations;

use App\Helpers\Helper;
use App\Models\Category;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService implements CategoryServiceInterface
{

    protected $categoryRepository;

    public function getAllCategories(): Collection
    {
        try {
            $categories = Category::get();

            if ($categories->isEmpty()) {
                throw new NotFoundHttpException('No categories found');
            }

            return $categories;
        } catch (\Throwable $e) {
            Helper::logException($e);

            throw $e;
        }
    }

    public function getSingleCategory($id): Category
    {
        try {
            $category = Category::findOrFail($id);

            return $category;
        } catch (\Throwable $e) {
            Helper::logException($e);

            throw $e;
        }
    }

    public function store($request): Category
    {
        DB::beginTransaction();

        try {
            $maxOrder = Category::max('order') + 1;

            $category = Category::create([
                'name' => $request['name'],
                'order' => $maxOrder
            ]);

            DB::commit();

            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function update($id, $request): Category
    {
        DB::beginTransaction();

        try {
            $category = Category::findOrFail($id);

            $category->update([
                'name' => $request['name']
            ]);

            DB::commit();

            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function softDelete($id): Category
    {
        DB::beginTransaction();

        try {
            $category = Category::findOrFail($id);

            $category->delete();

            DB::commit();

            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function forceDelete($id): Category
    {
        DB::beginTransaction();

        try {
            $category = Category::findOrFail($id);

            $category->forceDelete();

            DB::commit();

            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }
}
