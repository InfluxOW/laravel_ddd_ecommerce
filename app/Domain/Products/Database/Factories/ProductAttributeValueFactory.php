<?php

namespace App\Domain\Products\Database\Factories;

use App\Domain\Products\Enums\ProductAttributeType;
use App\Domain\Products\Models\ProductAttributeValue;
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
            $value = match ($productAttributeValue->attribute->type) {
                ProductAttributeType::BOOLEAN => $this->faker->boolean,
                ProductAttributeType::INTEGER => random_int(0, 1000),
                ProductAttributeType::FLOAT => $this->faker->randomFloat(random_int(1, 8), 0, 100),
                ProductAttributeType::STRING => $this->faker->words(3, true),
            };

            $productAttributeValue->value = $value;
        });
    }
}
