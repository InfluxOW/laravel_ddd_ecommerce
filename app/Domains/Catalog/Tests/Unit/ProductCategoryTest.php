<?php

namespace App\Domains\Catalog\Tests\Unit;

use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Common\Tests\TestCase;

final class ProductCategoryTest extends TestCase
{
    protected static array $seeders = [
        ProductCategorySeeder::class,
    ];

    /** @test */
    public function product_category_with_depth_higher_than_max_cannot_be_created(): void
    {
        $depth = ProductCategory::MAX_DEPTH + 1;

        /** @var ProductCategory $category */
        $category = ProductCategory::query()->hasLimitedDepth()->where('depth', ProductCategory::MAX_DEPTH)->first();
        $this->assertNotNull($category);

        $category->depth = $depth;
        $category->save();
        $this->assertNotEquals($category->refresh()->getDepth(), $depth);

        $newCategory = ProductCategory::forceCreate(['title' => 'test', 'is_visible' => true, 'is_displayable' => true]);
        $newCategory->parent()->associate($category);
        $this->assertNull($newCategory->refresh()->parent_id);

        $newCategory = $category->children()->create(['title' => 'test']);
        $this->assertFalse($newCategory->exists);
    }

    /** @test */
    public function product_categories_hierarchy_can_be_filtered_and_mapped(): void
    {
        $hierarchy = ProductCategory::filterHierarchy(static fn (ProductCategory $category): bool => $category->is_visible, ProductCategory::getHierarchy());
        $hierarchy = ProductCategory::mapHierarchy(static fn (ProductCategory $category): bool => $category->is_visible, $hierarchy);

        $this->assertTrue($hierarchy->every(fn (bool $isVisible): bool => $isVisible));
    }
}
