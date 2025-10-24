<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        
        return [
            'name' => $this->faker->unique()->sentence(3),
            'about' => $this->faker->text(500),
            'image' => 'image',
            'rating' => $this->faker->numberBetween(1, 5),
            'category_id' => Category::inRandomOrder()->first()->id,
            'order' => $this->faker->numberBetween(1, 10)
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function($book){
            $authors_ids = Author::inRandomOrder()->pluck('id')->take(2)->toArray();

            $book->authors()->attach($authors_ids);
        });
    }
}
