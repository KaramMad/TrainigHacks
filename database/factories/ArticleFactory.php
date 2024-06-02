<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $article=Article::class;
    public function definition(): array
    {

        return [
            'Author_name' => $this->faker->word(),
            'Article' => $this->faker->paragraph(2),
            'title' => $this->faker->sentence(2),
            'image' => 'Articls/images.jpg',
            'created_at' => '11 june 2003',

        ];
    }
}
