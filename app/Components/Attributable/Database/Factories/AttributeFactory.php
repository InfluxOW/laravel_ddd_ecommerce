<?php

namespace App\Components\Attributable\Database\Factories;

use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Models\Attribute;
use App\Domains\Common\Database\Factory;

final class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        return self::addTimestamps([
            'title' => $this->faker->words(3, true),
            'values_type' => $this->faker->randomElement(AttributeValuesType::cases()),
        ]);
    }
}
