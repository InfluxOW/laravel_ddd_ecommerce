<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Database\Factories\ProductFactory;
use App\Domains\Catalog\Models\Product;
use App\Infrastructure\Abstracts\Database\Seeder;

final class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 500;

        if (app()->isLocal()) {
            $count = 1000;
        }

        if (app()->runningUnitTests()) {
            $count = 50;
        }

        $productIds = $this->seedModelByChunks(Product::class, $count);

        $this->seedBelongsToManyRelationByChunks(
            Product::make()->categories(),
            $productIds,
            ProductFactory::getProductCategoriesIds()->toArray(),
            fn (array $categoryIds): array => ProductFactory::getRandomCategoriesIds(collect($categoryIds))->toArray()
        );
    }
}
