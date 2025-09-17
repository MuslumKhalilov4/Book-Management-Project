<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getAll(): Collection;

    public function find($id): Category;

    public function create($request): Category;

    public function update($data, $request): void;

    public function delete($data): void;
}