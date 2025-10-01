<?php

namespace Database\Factories;

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
        $books = [
            "To Kill a Mockingbird",
            "1984",
            "The Great Gatsby",
            "Pride and Prejudice",
            "The Catcher in the Rye",
            "The Lord of the Rings",
            "Harry Potter and the Sorcerer's Stone",
            "The Hobbit",
            "Brave New World",
            "Moby-Dick"
        ];

        return [
            'name' => $this->faker->unique()->randomElement($books),
            'about' => $this->faker->text(500),
            'image' => 'image',
            'rating' => $this->faker->numberBetween(1, 5),
            'category_id' => $this->faker->numberBetween(1, 5),
            'order' => $this->faker->unique()->numberBetween(1, 10)
        ];
    }
}
