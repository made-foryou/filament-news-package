<?php

namespace MadeForYou\News\Database\Factories;

use MadeForYou\News\Models\Post;
use MadeForYou\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),

            'title' => $this->faker->word(),

            'date' => $this->faker->dateTimeThisMonth(),

            'summary' => $this->faker->text(),
            'content' => '[]',

        ];
    }
}
