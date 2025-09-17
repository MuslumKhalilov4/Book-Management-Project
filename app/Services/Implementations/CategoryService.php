<?php

namespace App\Services\Implementations;

use App\Helpers\Helper;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService implements CategoryServiceInterface
{

    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function getAllCategories(): Collection
    {
        try {
            $categories = $this->categoryRepository->getAll();

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
            $category = $this->categoryRepository->find($id);

            if (!$category) {
                throw new ModelNotFoundException();
            }

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
            $request['order'] = Category::max('order') + 1;

            $category = $this->categoryRepository->create($request);

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
            $category = $this->categoryRepository->find($id);

            if (!$category) {
                throw new ModelNotFoundException();
            }

            $this->categoryRepository->update($category, $request);

            DB::commit();

            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function destroy($id): Category
    {
        DB::beginTransaction();

        try {
            $category = $this->categoryRepository->find($id);

            if (!$category) {
                throw new ModelNotFoundException();
            }

            $this->categoryRepository->delete($category);

            DB::commit();

            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }
}
