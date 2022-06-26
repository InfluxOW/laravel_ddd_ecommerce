<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Models\ProductCategory;
use App\Infrastructure\Abstracts\Database\Seeder;

final class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoriesMultiplier = app()->runningUnitTests() ? 1 : 3;

        ProductCategory::factory()
            ->count($categoriesMultiplier)
            ->has(ProductCategory::factory()
                ->count($categoriesMultiplier)
                ->has(ProductCategory::factory()
                    ->count($categoriesMultiplier)
                    ->has(ProductCategory::factory()
                        ->count($categoriesMultiplier), 'children'), 'children'), 'children')
            ->create();
    }
}
