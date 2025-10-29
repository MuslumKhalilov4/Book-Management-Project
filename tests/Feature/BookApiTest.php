<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_retrieved_successfully()
    {
        Category::factory()->create();
        Book::factory()->count(2)->create();
        Role::factory()->count(3)->create();
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/book');

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
        Role::factory()->count(3)->create();
        $user = User::factory()->create();

        $test_book = [
            'name' => ['en' => 'Created during test'],
            'about' => ['en' => 'Test description'],
            'rating' => 5,
            'category_id' => $category->id,
            'order' => 0,
            'author_ids' => $authors,
            'image' => UploadedFile::fake()->image('book.jpg')
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/book/store', $test_book);

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
        Role::factory()->count(3)->create();
        $user = User::factory()->create();

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

        $response = $this->actingAs($user, 'sanctum')->putJson('/api/book/update/' . $test_book->id, $update_data);

        $response->assertStatus(200)->assertJsonFragment(['name' => 'Updated during test']);
    }

    public function test_book_deleted_successfully()
    {
        Category::factory()->create();
        Role::factory()->count(3)->create();
        $user = User::factory()->create();

        $test_book = Book::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/book/destroy/{$test_book->id}/force");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('books', ['id' => $test_book->id]);
    }

    public function test_book_order_down_then_order_up_successfully()
    {
        Category::factory()->create();
        Role::factory()->count(3)->create();
        $user = User::factory()->create();
        Book::factory()->count(2)->create();

        $test_book = Book::orderBy('order')->first();
        $old_order = $test_book->order;
        $response = $this->actingAs($user, 'sanctum')->getJson("/api/book/order-down/{$test_book->id}");
        $response->assertStatus(200);
        $test_book->refresh();
        $this->assertEquals($old_order + 1, $test_book->order);

        $old_order2 = $test_book->order;
        $response2 = $this->actingAs($user, 'sanctum')->getJson("api/book/order-up/{$test_book->id}");
        $response2->assertStatus(200);
        $test_book->refresh();
        $this->assertEquals($old_order2 - 1, $test_book->order);
    }

    public function test_error_if_already_on_bottom(){
        Category::factory()->create();
        Role::factory()->count(3)->create();
        $user = User::factory()->create();
        Book::factory()->count(2)->create();

        $test_book = Book::orderBy('order', 'desc')->first();

        $response = $this->actingAs($user, 'sanctum')->getJson("api/book/order-down/{$test_book->id}");

        $response->assertStatus(400)->assertJsonFragment([
            'message' => class_basename($test_book) . " is already at the bottom position"
        ]);
    }

    public function test_error_id_user_is_not_authenticated(){
        Category::factory()->create();
        Book::factory()->count(2)->create();

        $response = $this->getJson('api/book');

        $response->assertStatus(401);
    }
}
