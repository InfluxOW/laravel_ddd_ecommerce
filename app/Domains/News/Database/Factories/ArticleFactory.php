<?php

namespace App\Domains\News\Database\Factories;

use App\Domains\News\Models\Article;
use App\Infrastructure\Abstracts\Database\Factory;
use Illuminate\Support\Str;

final class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->headline;

        $publishedAt = null;
        if ($this->faker->boolean(40)) {
            $publishedAt = $this->faker->dateTimeBetween('-1 year');
            /** @phpstan-ignore-next-line */
        } elseif ($this->faker->boolean(40)) {
            $publishedAt = $this->faker->dateTimeBetween('now', '+1 year');
        }

        return self::addTimestamps([
            'title' => $title,
            'slug' => Str::of($title)->slug(),
            'description' => $this->faker->realText(300),
            'body' => $this->faker->realText(2000),
            'published_at' => $publishedAt,
        ]);
    }
}
