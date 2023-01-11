<?php

namespace App\Domains\Catalog\Tests\Feature\Api;

use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;
use App\Domains\Common\Tests\TestCase;

final class ProductCategoryControllerTest extends TestCase
{
    protected static array $seeders = [
        ProductCategorySeeder::class,
        ProductSeeder::class,
    ];

    /** @test */
    public function a_user_can_view_categories_tree(): void
    {
        $this->get(route('categories.index'))->assertOk();
    }
}
