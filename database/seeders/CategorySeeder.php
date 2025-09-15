<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['az' => 'Komediya', 'en' => 'Comedy', 'ru' => 'Комедия'],
            ['az' => 'Fantastika', 'en' => 'Fantasy', 'ru' => 'Фэнтези'],
            ['az' => 'Dram', 'en' => 'Drama', 'ru' => 'Драма'],
            ['az' => 'Qorxu', 'en' => 'Horror', 'ru' => 'Ужасы'],
            ['az' => 'Romantika', 'en' => 'Romance', 'ru' => 'Романтика'],
        ];


        $order = 1;

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'name' => $category,
                'order' => $order
            ]);

            $order++;
        }
    }
}
