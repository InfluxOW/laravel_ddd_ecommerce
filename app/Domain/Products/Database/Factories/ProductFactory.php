<?php

namespace App\Domain\Products\Database\Factories;

use App\Domain\Products\Models\Generic\Kopecks;
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
            ->afterMaking(function (Product $product): void {
                $category = ProductCategory::query()
                    ->where('depth', '>=', ProductCategory::MAX_DEPTH - 1)
                    ->limitDepth(ProductCategory::MAX_DEPTH)
                    ->inRandomOrder()
                    ->first();

                $product->category()->associate($category);
            });
    }
}
