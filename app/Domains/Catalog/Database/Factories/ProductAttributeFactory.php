<?php

namespace App\Domains\Catalog\Database\Factories;

use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Catalog\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProductAttributeFactory extends Factory
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
