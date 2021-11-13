<?php

namespace Tests\Feature;

use Database\Seeders\ProductCategorySeeder;
use Tests\TestCase;

class ProductCategoryControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            ProductCategorySeeder::class,
        ]);
    }

    /** @test */
    public function a_user_can_view_categories_tree(): void
    {
        $this->get('api/categories')->assertOk();
    }
}
