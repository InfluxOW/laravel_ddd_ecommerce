<?php

namespace App\Domain\Products\Database\Factories;

use App\Domain\Products\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
        ];
    }
}
