<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoriesCountByCategoryDepth = [3, 9, 27, 81];
        [$rootCategoriesCount, $firstLevelCategoriesCount, $secondLevelCategoriesCount, $thirdLevelCategoriesCount] = $categoriesCountByCategoryDepth;

        ProductCategory::factory()
            ->count($rootCategoriesCount)
            ->has(ProductCategory::factory()
                ->count($firstLevelCategoriesCount / $rootCategoriesCount)
                ->has(ProductCategory::factory()
                    ->count($secondLevelCategoriesCount / $firstLevelCategoriesCount)
                    /*
                     * Laravel Factories doesn't support 3+ depth
                     * so we have to create 3+ level categories in such way
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
