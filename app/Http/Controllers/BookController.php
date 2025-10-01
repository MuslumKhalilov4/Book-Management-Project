<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\Implementations\SortOrderService;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    protected $bookService;
    protected $sortOrderService;

    public function __construct(BookServiceInterface $bookService, SortOrderService $sortOrderService)
    {
        $this->bookService = $bookService;
        $this->sortOrderService = $sortOrderService;
    }

    public function index(): JsonResponse
    {
        $authors = $this->bookService->getAllBooks();

        return Helper::successResponse('Books retrieved successfully!', BookResource::collection($authors), 200);
    }

    public function show($id): JsonResponse
    {
        $author = $this->bookService->getSingleBook($id);

        return Helper::successResponse('Book retrieved successfully!', BookResource::make($author), 200);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $author = $this->bookService->store($request->validated());

        return Helper::successResponse('Book created successfully!', BookResource::make($author), 201);
    }

    public function update($id, UpdateBookRequest $request): JsonResponse
    {
        $author = $this->bookService->update($id, $request->validated());

        return Helper::successResponse('Book updated successfully!', BookResource::make($author), 200);
    }


    public function softDelete($id): JsonResponse
    {
        $author = $this->bookService->softDelete($id);

        return Helper::successResponse('Book deleted successfully!', BookResource::make($author), 200);
    }

    public function forceDelete($id): JsonResponse
    {
        $author = $this->bookService->forceDelete($id);

        return Helper::successResponse('Book permanently deleted successfully!', BookResource::make($author), 200);
    }

    public function orderUp($id): JsonResponse
    {
        try {
            $author = $this->sortOrderService->orderUp($id, new Book());

            return Helper::successResponse('Book successfully moved up!', BookResource::make($author), 200);
        } catch (\Exception $e) {
            return Helper::failResponse($e->getMessage(), 400);
        }
    }

    public function orderDown($id): JsonResponse
    {
        try {
            $author = $this->sortOrderService->orderDown($id, new Book());

            return Helper::successResponse('Book successfully moved down!', BookResource::make($author), 200);
        } catch (\Exception $e) {
            return Helper::failResponse($e->getMessage(), 400);
        }
    }
}
