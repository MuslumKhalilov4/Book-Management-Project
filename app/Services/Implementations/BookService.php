<?php

namespace App\Services\Implementations;

use App\Helpers\Helper;
use App\Models\Book;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookService implements BookServiceInterface
{
    public function getAllBooks(): Collection
    {
        $books = Book::with('authors')->get();

        return $books;
    }

    public function getSingleBook($id): Book
    {
        $book = Book::with('authors')->findOrFail($id);

        return $book;
    }

    public function store($request): Book
    {
        DB::beginTransaction();

        try {
            $maxOrder = Book::max('order') + 1;

            $image_path = isset($request['image']) ? Helper::uploadImage($request['image'], 'Book') : null;

            $book = Book::create([
                'name' => $request['name'],
                'about' => $request['about'],
                'image' => $image_path,
                'category_id' => $request['category_id'],
                'order' => $maxOrder,
                'rating' => 0
            ]);

            if (isset($request['author_ids'])) {
                $book->authors()->attach($request['author_ids']);
            }

            DB::commit();

            return $book;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function update($id, $request): Book
    {
        DB::beginTransaction();

        try {
            $book = Book::findOrFail($id);

            $update_datas = [
                'name' => $request['name'],
                'about' => $request['about'],
                'category_id' => $request['category_id']
            ];

            if ($request['image']) {
                if ($book->image) {
                    Helper::deleteFile($book->image);
                }

                $update_datas['image'] = Helper::uploadImage($request['image'], 'Book');
            }

            $book->update($update_datas);

            if (isset($request['author_ids'])) {
                $book->authors()->sync($request['author_ids']);
            }

            DB::commit();

            return $book;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function softDelete($id): Book
    {
        $book = Book::findOrFail($id);

        $book->delete();

        return $book;
    }

    public function forceDelete($id): Book
    {
        $book = Book::findOrFail($id);

        if ($book->image) {
            Helper::deleteFile($book->image);
        }

        $book->forceDelete();

        return $book;
    }
}
