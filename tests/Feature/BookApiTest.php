<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    public function test_books_retrieved_successfully()
    {
        $response = $this->getJson('/api/book');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'about',
                    'rating',
                    'category',
                    'order',
                    'image',
                    'authors' => [
                        '*' => [
                            'id',
                            'full_name',
                            'about',
                            'image_path',
                            'order'
                        ]
                    ]
                ]
            ]
        ]);
    }


    public function test_book_created_successfully()
    {
        $authors = Author::factory()->count(2)->create()->pluck('id')->toArray();
        $category = Category::factory()->create();

        $test_book = [
            'name' => ['en' => 'Created during test'],
            'about' => ['en' => 'Test description'],
            'rating' => 5,
            'category_id' => $category->id,
            'order' => 0,
            'author_ids' => $authors,
            'image' => UploadedFile::fake()->image('book.jpg')
        ];

        $response = $this->postJson('/api/book/store', $test_book);

        $response->assertStatus(201)->assertJsonFragment(['name' => 'Created during test']);

        $this->assertDatabaseHas('books', [
            'name->en' => 'Created during test',
            'about->en' => 'Test description'
        ]);
    }

    public function test_book_updated_successfully()
    {
        $authors = Author::factory()->count(2)->create()->pluck('id')->toArray();
        $category = Category::factory()->create();

        $test_book = Book::factory()->create();

        $update_data = [
            'name' => ['en' => 'Updated during test'],
            'about' => ['en' => 'Test description'],
            'rating' => 5,
            'category_id' => $category->id,
            'order' => 0,
            'image' => UploadedFile::fake()->image('book.jpg'),
            'author_ids' => $authors
        ];

        $response = $this->putJson('/api/book/update/' . $test_book->id, $update_data);

        $response->assertStatus(200)->assertJsonFragment(['name' => 'Updated during test']);
    }

    public function test_book_deleted_successfully(){
        $test_book = Book::factory()->create();

        $response = $this->deleteJson("/api/book/destroy/{$test_book->id}/force");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('books', ['id' => $test_book->id]);
    }
}
