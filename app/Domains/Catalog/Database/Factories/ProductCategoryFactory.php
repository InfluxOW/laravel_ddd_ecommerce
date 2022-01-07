<?php

namespace App\Domains\Catalog\Database\Factories;

use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->realText(300),
            'is_visible' => true,
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
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
