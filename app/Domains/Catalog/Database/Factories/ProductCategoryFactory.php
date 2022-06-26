<?php

namespace App\Domains\Catalog\Database\Factories;

use App\Domains\Catalog\Models\ProductCategory;
use App\Infrastructure\Abstracts\Database\Factory;
use Illuminate\Support\Str;

final class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        /** @var string $title */
        $title = $this->faker->unique()->words(3, true);

        return self::addTimestamps([
            'title' => $title,
            'slug' => Str::of($title)->slug(),
            'description' => $this->faker->realText(300),
            'is_visible' => true,
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
        ]);
    }

    public function configure(): self
    {
        return $this
            ->afterCreating(function (ProductCategory $category): void {
                $category->is_visible = $this->faker->boolean(100 - $category->depth * 10);

                $category->save();
            });
    }
}
