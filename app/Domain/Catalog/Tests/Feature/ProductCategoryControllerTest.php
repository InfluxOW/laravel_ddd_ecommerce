<?php

namespace App\Domain\Catalog\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domain\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domain\Catalog\Database\Seeders\ProductSeeder;

class ProductCategoryControllerTest extends TestCase
{
    protected function setUpOnce(): void
    {
        $this->seed([
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);
    }

    /** @test */
    public function a_user_can_view_categories_tree(): void
    {
        $this->get(route('categories.index'))->assertOk();
    }
}
