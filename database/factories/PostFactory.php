<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence;
        $content = $this->faker->paragraphs(5, true);
        
        // Available post images
        $postImages = [
            'images/posts/post1.jpg',
            'images/posts/post2.jpg',
            'images/posts/post3.jpg',
            'images/posts/post4.jpg',
            'images/posts/post5.jpg',
            'images/posts/post6.jpg',
            'images/posts/post7.jpg',
            'images/posts/post8.jpg',
            'images/posts/post9.jpg',
            'images/posts/post10.jpg',
        ];
        
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->paragraph,
            'content' => $content,
            'status' => $this->faker->randomElement(['draft', 'published']),
            'view_count' => $this->faker->numberBetween(0, 1000),
            'image' => $this->faker->randomElement($postImages),
        ];
    }
    
    public function published(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
            ];
        });
    }
    
    public function draft(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft',
            ];
        });
    }
} 