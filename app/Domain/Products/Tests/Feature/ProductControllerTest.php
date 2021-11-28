<?php

namespace App\Domain\Products\Tests\Feature;

use App\Domain\Products\Database\Seeders\ProductAttributeSeeder;
use App\Domain\Products\Database\Seeders\ProductAttributeValueSeeder;
use App\Domain\Products\Database\Seeders\ProductCategorySeeder;
use App\Domain\Products\Database\Seeders\ProductSeeder;
use App\Application\Tests\TestCase;

class ProductControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            ProductCategorySeeder::class,
            ProductAttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
//        $response = $this->get(route('products.index', ['filter[category]' => 'test']));

//        dd(json_decode($response->getContent()));
    }
}
