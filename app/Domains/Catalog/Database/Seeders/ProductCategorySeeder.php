<?php

namespace App\Domains\Catalog\Database\Seeders;

use App\Domains\Catalog\Console\Commands\UpdateProductCategoriesDisplayability;
use App\Domains\Catalog\Models\ProductCategory;
use App\Infrastructure\Abstracts\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

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

        Artisan::call(UpdateProductCategoriesDisplayability::class);
    }
}
