<?php

namespace App\Domain\Products\Database\Factories;

use Akaunting\Money\Currency;
use App\Domain\Products\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductPriceFactory extends Factory
{
    protected $model = ProductPrice::class;

    public function definition(): array
    {
        return [
            'currency' => $this->faker->randomKey(Currency::getCurrencies()),
            'price' => fn (array $attributes): int => $this->faker->numberBetween(100, 10000),
            'price_discounted' => fn (array $attributes): ?int => $this->faker->boolean(70) ? null : $this->faker->numberBetween((int)($attributes['price'] / 2), $attributes['price']),
        ];
    }
}
