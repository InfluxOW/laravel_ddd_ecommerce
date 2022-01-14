<?php

namespace App\Domains\Catalog\Database\Factories;

use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Catalog\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeValueFactory extends Factory
{
    protected $model = ProductAttributeValue::class;

    public function definition(): array
    {
        return [];
    }

    public function configure(): self
    {
        return $this->afterMaking(function (ProductAttributeValue $productAttributeValue): void {
            $value = match ($productAttributeValue->attribute->values_type) {
                ProductAttributeValuesType::BOOLEAN => $this->faker->boolean,
                ProductAttributeValuesType::INTEGER => random_int(0, 1000),
                ProductAttributeValuesType::FLOAT => $this->faker->randomFloat(random_int(1, 8), 0, 100),
                ProductAttributeValuesType::STRING => $this->faker->words(3, true),
            };

            $productAttributeValue->value = $value;
        });
    }
}