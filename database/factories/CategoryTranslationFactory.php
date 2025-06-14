<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryTranslationFactory extends Factory
{
    protected $model = CategoryTranslation::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'locale' => $this->faker->randomElement(['en', 'pl', 'de', 'fr']),
            'name' => $this->faker->words(2, true),
        ];
    }
} 