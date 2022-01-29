<?php

namespace App\Domains\Catalog\Database\Factories;

use App\Domains\Catalog\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProductPriceFactory extends Factory
{
    protected $model = ProductPrice::class;

    public function definition(): array
    {
        return [
            'price' => fn (array $attributes): int => $this->faker->numberBetween(100, 10000),
            'price_discounted' => fn (array $attributes): ?int => $this->faker->boolean(70) ? null : $this->faker->numberBetween((int) ($attributes['price'] / 2), $attributes['price']),
        ];
    }
}
