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
        $categories = Category::get();

        return $categories;
    }

    public function getSingleCategory($id): Category
    {
        $category = Category::findOrFail($id);

        return $category;
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
        $category = Category::findOrFail($id);

        $category->delete();

        return $category;
    }

    public function forceDelete($id): Category
    {
        $category = Category::findOrFail($id);

        $category->forceDelete();

        return $category;
    }
}
