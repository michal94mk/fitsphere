<?php

namespace Database\Factories;

use App\Models\PostTranslation;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostTranslationFactory extends Factory
{
    protected $model = PostTranslation::class;

    public function definition(): array
    {
        $title = $this->faker->sentence;
        
        return [
            'post_id' => Post::factory(),
            'locale' => $this->faker->randomElement(['en', 'pl']),
            'title' => $title,
            'excerpt' => $this->faker->paragraph,
            'content' => $this->faker->paragraphs(5, true),
        ];
    }
} 