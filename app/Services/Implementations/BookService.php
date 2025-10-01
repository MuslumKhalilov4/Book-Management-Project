<?php

namespace App\Services\Implementations;

use App\Helpers\Helper;
use App\Models\Book;
use App\Services\Interfaces\AuthorServiceInterface;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookService implements BookServiceInterface
{
    public function getAllBooks(): Collection
    {
        try {
            $books = Book::get();

            if ($books->isEmpty()) {
                throw new NotFoundHttpException('No books found');
            }

            return $books;
        } catch (\Throwable $e) {
            Helper::logException($e);

            throw $e;
        }
    }

    public function getSingleBook($id): Book
    {
        try {
            $books = Book::findOrFail($id);

            return $books;
        } catch (\Throwable $e) {
            Helper::logException($e);

            throw $e;
        }
    }

    public function store($request): Book
    {
        DB::beginTransaction();

        try {
            $maxOrder = Book::max('order') + 1;

            $image_path = '';

            if ($request['image']) {
                $image_path = Helper::uploadImage($request['image'], 'Book');
            }

            $book = Book::create([
                'name' => $request['name'],
                'about' => $request['about'],
                'image' => $image_path,
                'category_id' => $request['category_id'],
                'order' => $maxOrder
            ]);

            foreach ($request['author_ids'] as $author_id) {
                $book->authors()->attach($author_id);
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

            foreach ($request['author_ids'] as $author_id) {
                $book->authors()->sync($author_id);
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
        DB::beginTransaction();

        try {
            $book = Book::findOrFail($id);

            $book->delete();

            DB::commit();

            return $book;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function forceDelete($id): Book
    {
        DB::beginTransaction();

        try {
            $book = Book::findOrFail($id);

            if($book->image){
                Helper::deleteFile($book->image);
            }

            $book->forceDelete();

            DB::commit();

            return $book;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    } 
}
