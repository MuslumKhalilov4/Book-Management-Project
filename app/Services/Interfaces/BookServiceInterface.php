<?php

namespace App\Services\Interfaces;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceInterface
{
    public function getAllBooks(): Collection;

    public function getSingleBook($id): Book;

    public function store($request): Book;

    public function update($id, $request): Book;

    public function softDelete($id): Book;

    public function forceDelete($id): Book;
}