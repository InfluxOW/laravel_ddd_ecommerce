<?php

namespace App\Domain\Products\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domain\Generic\Query\Enums\QueryKey;
use App\Domain\Generic\Response\Enums\ResponseKey;
use App\Domain\Products\Database\Seeders\ProductAttributeSeeder;
use App\Domain\Products\Database\Seeders\ProductAttributeValueSeeder;
use App\Domain\Products\Database\Seeders\ProductCategorySeeder;
use App\Domain\Products\Database\Seeders\ProductSeeder;
use App\Domain\Products\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Products\Models\Product;

class ProductControllerTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            ProductCategorySeeder::class,
            ProductAttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
        ]);

        /** @var Product $product */
        $product = Product::query()->first();

        $this->product = $product;
    }

    /** @test */
    public function a_user_can_view_products_list(): void
    {
        $this->get(route('products.index'))->assertOk();
    }

    /** @test */
    public function a_user_can_filter_products_by_title(): void
    {
        $response = $this->get(route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::TITLE->value => $this->product->title]]))->assertOk();
        $items = collect($response->json(ResponseKey::DATA->value));
        $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

        $this->assertNotEmpty($items);
        $this->assertTrue($items->every(fn (array $item) => str_contains($item['title'], $this->product->title)));

        $this->assertCount(1, $appliedFilters);
        $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::TITLE->value));
    }

    /** @test */
    public function a_user_can_filter_products_by_description(): void
    {
        $response = $this->get(route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::DESCRIPTION->value => $this->product->description]]))->assertOk();
        $items = collect($response->json(ResponseKey::DATA->value));
        $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

        $this->assertNotEmpty($items);
        $this->assertTrue($items->every(fn (array $item) => str_contains($item['description'], $this->product->description)));

        $this->assertCount(1, $appliedFilters);
        $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::DESCRIPTION->value));
    }

    /** @test */
    public function a_user_can_view_specific_product(): void
    {
        $this->get(route('products.show', $this->product))->assertOk();
    }

    /** @test */
    public function a_user_cannon_view_nonexistent_product(): void
    {
        $this->get(route('products.show', 'wrong_product'))->assertNotFound();
    }
}
