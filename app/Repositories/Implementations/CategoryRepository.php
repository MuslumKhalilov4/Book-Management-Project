<?php

namespace App\Repositories\Implementations;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(): Collection
    {   
        $categories = Category::get();

        return $categories;
    }

    public function find($id): Category
    {
        $category = Category::findOrFail($id);

        return $category;
    }

    public function create($request): Category
    {
        $category = Category::create([
            'name' => $request['name'],
            'order' => $request['order']
        ]);

        return $category;
    }

    public function update($data, $request): void
    {
        $data->update([
            'name' => $request['name']
        ]);
    }

    public function delete($data): void
    {
        $data->delete();
    }
}