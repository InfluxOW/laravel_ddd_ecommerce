<?php

namespace App\Components\Purchasable\Database\Factories;

use App\Components\Purchasable\Models\Price;
use App\Domains\Common\Database\Factory;

final class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return self::addTimestamps([
            'price' => fn (array $attributes): int => $this->faker->numberBetween(100, 10000),
            'price_discounted' => fn (array $attributes): ?int => $this->faker->boolean(70) ? null : $this->faker->numberBetween((int) ($attributes['price'] / 2), $attributes['price']),
        ]);
    }
}
