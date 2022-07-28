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
        /** @var string $title */
        $title = $this->faker->words(3, true);

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
            'description' => $this->faker->text(1000),
            'body' => $this->faker->text(2000),
            'published_at' => $publishedAt,
        ]);
    }
}
