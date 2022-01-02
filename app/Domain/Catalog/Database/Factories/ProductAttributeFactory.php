<?php

namespace App\Domain\Catalog\Database\Factories;

use App\Domain\Catalog\Enums\ProductAttributeValuesType;
use App\Domain\Catalog\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'values_type' => $this->faker->randomElement(ProductAttributeValuesType::cases()),
        ];
    }
}
