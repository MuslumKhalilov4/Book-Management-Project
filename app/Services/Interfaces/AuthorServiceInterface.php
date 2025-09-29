<?php

namespace App\Services\Interfaces;

use App\Http\Requests\Author\StoreAuthorRequest;
use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

interface AuthorServiceInterface
{
    public function getAllAuthors(): Collection;

    public function getSingleAuthor($id): Author;

    public function store($request): Author;

    public function update($id, $request): Author;

    public function softDelete($id): Author;

    public function forceDelete($id): Author;

    public function orderUp($id): Author;

    public function orderDown($id): Author;
}