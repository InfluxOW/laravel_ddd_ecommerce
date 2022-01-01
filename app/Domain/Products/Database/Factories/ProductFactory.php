<?php

namespace App\Domain\Products\Database\Factories;

use App\Domain\Generic\Currency\Models\Kopecks;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $basePrice = $this->faker->numberBetween(100, 10000) * Kopecks::KOPECKS_IN_ROUBLE;

        return [
            'title' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->words(50, true),
            'price' => $basePrice,
            'price_discounted' => $this->faker->boolean(70) ? null : $this->faker->numberBetween(50 * Kopecks::KOPECKS_IN_ROUBLE, $basePrice),
            'created_at' => $this->faker->dateTimeBetween('-2 years'),
        ];
    }

    public function configure(): self
    {
        return $this
            ->afterCreating(function (Product $product): void {
                $categories = ProductCategory::query()
                    ->where('depth', '>=', ProductCategory::MAX_DEPTH - 1)
                    ->hasLimitedDepth()
                    ->inRandomOrder()
                    ->limit(random_int(1, 5))
                    ->get();

                $product->categories()->sync($categories);
            });
    }
}
