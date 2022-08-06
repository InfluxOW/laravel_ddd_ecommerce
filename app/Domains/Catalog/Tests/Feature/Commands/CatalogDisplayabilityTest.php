<?php

namespace App\Domains\Catalog\Tests\Feature\Commands;

use App\Application\Tests\TestCase;
use App\Domains\Catalog\Console\Commands\UpdateProductCategoriesDisplayability;
use App\Domains\Catalog\Console\Commands\UpdateProductsDisplayability;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;

final class CatalogDisplayabilityTest extends TestCase
{
    protected static array $seeders = [
        ProductCategorySeeder::class,
        ProductSeeder::class,
        ProductPriceSeeder::class,
    ];

    protected function setUpOnce(): void
    {
        parent::setUpOnce();

        ProductCategory::query()->update(['is_visible' => true, 'is_displayable' => true]);
        Product::query()->update(['is_visible' => true, 'is_displayable' => true]);

        ProductCategory::loadHierarchy();
    }

    /** @test */
    public function catalog_displayability_may_be_updated(): void
    {
        /** @var ProductCategory $category */
        $category = ProductCategory::query()->displayable()->withWhereHas('products')->first();
        $this->assertNotNull($category);

        /** @var Product $product */
        $product = $category->products->random();
        $this->assertNotNull($product);

        $setProductVisibility = function (bool $visibility) use ($product): void {
            $product->is_visible = $visibility;
            $product->save();

            $this->artisan(UpdateProductsDisplayability::class);
        };

        $setProductCategoryVisibility = function (bool $visibility) use ($category): void {
            $category->is_visible = $visibility;
            $category->save();

            $this->artisan(UpdateProductCategoriesDisplayability::class);
            $this->artisan(UpdateProductsDisplayability::class);
        };

        $setProductCategoryVisibility(false);
        $setProductVisibility(false);

        $this->get(route('products.show', $product))->assertNotFound();

        $setProductVisibility(true);

        $this->get(route('products.show', $product))->assertNotFound();

        $setProductCategoryVisibility(true);

        $this->get(route('products.show', $product))->assertOk();
    }
}
