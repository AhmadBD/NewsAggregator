<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Article>
 */
final class ArticleFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Article::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'title' => fake()->title,
            'content' => fake()->optional()->text,
            'image_url' => fake()->optional()->url,
            'description' => fake()->optional()->text,
            'url' => fake()->optional()->url,
            'published_at' => fake()->optional()->dateTime(),
            'source' => fake()->optional()->word,
            'author' => fake()->optional()->word,
            'country_id' => Country::first()?->id,
            'category_id' => Category::first()?->id,
        ];
    }
}
