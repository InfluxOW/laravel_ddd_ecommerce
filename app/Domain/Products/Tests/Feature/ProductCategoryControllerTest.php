<?php

namespace App\Domain\Products\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domain\Products\Database\Seeders\ProductCategorySeeder;
use App\Domain\Products\Database\Seeders\ProductSeeder;

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
