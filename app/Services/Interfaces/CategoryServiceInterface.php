<?php

namespace App\Services\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

interface CategoryServiceInterface
{
    public function getAllCategories(): Collection;

    public function getSingleCategory($id): Category;

    public function store($request): Category;

    public function update($id, $request): Category;

    public function softDelete($id): Category;

    public function forceDelete($id): Category;
}