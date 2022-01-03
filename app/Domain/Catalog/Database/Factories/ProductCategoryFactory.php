<?php

namespace App\Domain\Catalog\Database\Factories;

use App\Domain\Catalog\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->realText(300),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }
}
