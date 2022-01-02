<?php

namespace App\Domain\Catalog\Database\Factories;

use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->words(50, true),
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
                    ->limit(app()->runningUnitTests() ? 1 : random_int(1, 5))
                    ->get();

                $product->categories()->sync($categories);
            });
    }
}
