<?php

namespace App\Http\Controllers;

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
        $books = $this->bookService->getAllBooks();

        return $this->successResponse('Books retrieved successfully!', BookResource::collection($books), 200);
    }

    public function show($id): JsonResponse
    {
        $book = $this->bookService->getSingleBook($id);

        return $this->successResponse('Book retrieved successfully!', BookResource::make($book), 200);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->store($request->validated());

        return $this->successResponse('Book created successfully!', BookResource::make($book), 201);
    }

    public function update($id, UpdateBookRequest $request): JsonResponse
    {
        $book = $this->bookService->update($id, $request->validated());

        return $this->successResponse('Book updated successfully!', BookResource::make($book), 200);
    }


    public function softDelete($id): JsonResponse
    {
        $book = $this->bookService->softDelete($id);

        return $this->successResponse('Book deleted successfully!', BookResource::make($book), 200);
    }

    public function forceDelete($id): JsonResponse
    {
        $book = $this->bookService->forceDelete($id);

        return $this->successResponse('Book permanently deleted successfully!', BookResource::make($book), 200);
    }

    public function orderUp($id): JsonResponse
    {
        try {
            $book = $this->sortOrderService->orderUp($id, new Book());

            return $this->successResponse('Book successfully moved up!', BookResource::make($book), 200);
        } catch (\Exception $e) {
            return $this->failResponse($e->getMessage(), 400);
        }
    }

    public function orderDown($id): JsonResponse
    {
        try {
            $book = $this->sortOrderService->orderDown($id, new Book());

            return $this->successResponse('Book successfully moved down!', BookResource::make($book), 200);
        } catch (\Exception $e) {
            return $this->failResponse($e->getMessage(), 400);
        }
    }
}
