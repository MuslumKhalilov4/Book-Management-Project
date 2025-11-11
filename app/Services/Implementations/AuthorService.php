<?php

namespace App\Services\Implementations;

use App\Helpers\Helper;
use App\Models\Author;
use App\Services\Interfaces\AuthorServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorService implements AuthorServiceInterface
{
    public function getAllAuthors(): Collection
    {
        $authors = Author::get();

        return $authors;
    }

    public function getSingleAuthor($id): Author
    {
        $author = Author::findOrFail($id);

        return $author;
    }

    public function store($request): Author
    {
        DB::beginTransaction();

        try {
            $maxOrder = Author::max('order') + 1;

            $image_path = isset($request['image']) ? Helper::uploadImage($request['image'], 'Author') : null;

            $author = Author::create([
                'full_name' => $request['full_name'],
                'about' => $request['about'],
                'image' => $image_path,
                'order' => $maxOrder
            ]);

            DB::commit();

            return $author;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function update($id, $request): Author
    {
        DB::beginTransaction();

        try {
            $author = Author::findOrFail($id);

            $update_datas = [
                'full_name' => $request['full_name'],
                'about' => $request['about'],
            ];

            if ($request['image']) {
                if ($author->image) {
                    Helper::deleteFile($author->image);
                }

                $update_datas['image'] = Helper::uploadImage($request['image'], 'Author');
            }

            $author->update($update_datas);

            DB::commit();

            return $author;
        } catch (\Throwable $e) {
            DB::rollBack();

            Helper::logException($e);

            throw $e;
        }
    }

    public function softDelete($id): Author
    {
        $author = Author::findOrFail($id);

        $author->delete();

        return $author;
    }

    public function forceDelete($id): Author
    {
        $author = Author::findOrFail($id);

        if ($author->image) {
            Helper::deleteFile($author->image);
        }

        $author->forceDelete();

        return $author;
    }
}
