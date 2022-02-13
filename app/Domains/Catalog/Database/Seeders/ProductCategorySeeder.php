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
        $baseCategoriesCountMultiplier = app()->runningUnitTests() ? 1 : 3;
        $categoriesCountByCategoryDepth = [$baseCategoriesCountMultiplier, $baseCategoriesCountMultiplier * 3, $baseCategoriesCountMultiplier * (3 ** 2), $baseCategoriesCountMultiplier * (3 ** 3)];
        [$rootCategoriesCount, $firstLevelCategoriesCount, $secondLevelCategoriesCount, $thirdLevelCategoriesCount] = $categoriesCountByCategoryDepth;

        ProductCategory::factory()
            ->count($rootCategoriesCount)
            ->has(ProductCategory::factory()
                ->count($firstLevelCategoriesCount / $rootCategoriesCount)
                ->has(ProductCategory::factory()
                    ->count($secondLevelCategoriesCount / $firstLevelCategoriesCount)
                    /*
                     * Laravel Factories doesn't support 3+ depth
                     * so we have to create 3+ level categories in a such way
                     * */
                    ->afterCreating(function (ProductCategory $parent) use ($thirdLevelCategoriesCount, $secondLevelCategoriesCount): void {
                        /** @var iterable $categories */
                        $categories = ProductCategory::factory()
                            ->count($thirdLevelCategoriesCount / $secondLevelCategoriesCount)
                            ->make();

                        $parent->children()->saveMany($categories);
                    }), 'children'), 'children')
            ->create();
    }
}
