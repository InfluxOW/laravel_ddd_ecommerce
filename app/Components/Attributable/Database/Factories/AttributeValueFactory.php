<?php

namespace App\Components\Attributable\Database\Factories;

use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Models\AttributeValue;
use App\Infrastructure\Abstracts\Database\Factory;

final class AttributeValueFactory extends Factory
{
    protected $model = AttributeValue::class;

    public function definition(): array
    {
        return self::addTimestamps([]);
    }

    public function configure(): self
    {
        return $this->afterMaking(function (AttributeValue $productAttributeValue): void {
            /** @var bool|float|int|string $value */
            $value = match ($productAttributeValue->attribute->values_type) {
                AttributeValuesType::BOOLEAN => $this->faker->boolean,
                AttributeValuesType::INTEGER => random_int(0, 1000),
                AttributeValuesType::FLOAT => $this->faker->randomFloat(random_int(1, 8), 0, 100),
                AttributeValuesType::STRING => $this->faker->words(3, true),
            };

            $productAttributeValue->value = $value;
        });
    }
}
