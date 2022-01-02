<?php

namespace App\Domain\Catalog\Tests\Unit;

use App\Application\Tests\TestCase;
use App\Domain\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domain\Catalog\Models\ProductCategory;

class ProductCategoryTest extends TestCase
{
    protected function setUpOnce(): void
    {
        $this->seed([
            ProductCategorySeeder::class,
        ]);
    }

    /** @test */
    public function product_category_with_depth_higher_than_max_cannot_be_created(): void
    {
        $depth = ProductCategory::MAX_DEPTH + 1;

        /** @var ProductCategory $category */
        $category = ProductCategory::query()->hasLimitedDepth()->where('depth', ProductCategory::MAX_DEPTH)->first();
        $this->assertNotNull($category);

        $category->depth = $depth;
        $category->save();
        $this->assertNotEquals($category->refresh()->depth, $depth);

        $newCategory = ProductCategory::create(['title' => 'test']);
        $newCategory->parent()->associate($category);
        $this->assertNull($newCategory->refresh()->parent_id);

        $newCategory = $category->children()->create(['title' => 'test']);
        $this->assertFalse($newCategory->exists);
    }
}
