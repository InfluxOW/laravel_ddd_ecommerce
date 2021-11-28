<?php

namespace App\Domain\Products\Database\Factories;

use App\Domain\Products\Enums\ProductAttributeType;
use App\Domain\Products\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement(ProductAttributeType::cases()),
        ];
    }
}
