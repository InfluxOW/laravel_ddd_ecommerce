<?php

namespace App\Domain\Catalog\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domain\Catalog\Database\Seeders\ProductAttributeSeeder;
use App\Domain\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domain\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domain\Catalog\Database\Seeders\ProductSeeder;
use App\Domain\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductCategory;
use App\Domain\Generic\Query\Enums\QueryKey;
use App\Domain\Generic\Response\Enums\ResponseKey;

class ProductControllerTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Product $product */
        $product = Product::first();

        $this->product = $product;
    }

    protected function setUpOnce(): void
    {
        $this->seed([
            ProductCategorySeeder::class,
            ProductAttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
        ]);
    }

    /** @test */
    public function a_user_can_view_products_list(): void
    {
        $this->get(route('products.index'))->assertOk();
    }

    /** @test */
    public function a_user_can_filter_products_by_title(): void
    {
        $queries = [$this->product->title, trim(substr($this->product->title, 0, 5))];

        foreach ($queries as $query) {
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::TITLE->value => $query]]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            $this->assertNotEmpty($items);
            $this->assertTrue($items->every(fn (array $item): bool => str_contains($item['title'], $query)));

            $this->assertCount(1, $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::TITLE->value));
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_description(): void
    {
        $queries = [$this->product->description, trim(substr($this->product->description, 0, 5))];

        foreach ($queries as $query) {
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::DESCRIPTION->value => $query]]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            $this->assertNotEmpty($items);
            $this->assertTrue($items->every(fn (array $item): bool => str_contains($item['description'], $query)));

            $this->assertCount(1, $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::DESCRIPTION->value));
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_category(): void
    {
        $deepestCategory = ProductCategory::query()->hasLimitedDepth()->whereHas('products')->where('depth', ProductCategory::MAX_DEPTH)->first();
        $this->assertNotNull($deepestCategory);

        $product = $deepestCategory?->products->first();
        $this->assertNotNull($product);

        $productsCount = Product::query()->count();
        $category = $deepestCategory;
        while (isset($category)) {
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::CATEGORY->value => $category->slug], QueryKey::PER_PAGE->value => $productsCount]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            $this->assertNotEmpty($items);
            $this->assertTrue($items->pluck('slug')->contains($product?->slug));

            $this->assertCount(1, $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::CATEGORY->value));
            $this->assertTrue(collect($appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::CATEGORY->value)->first()['values'])->contains($category->slug));

            $category = $category->parent;
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_current_price(): void
    {
        $basePrice = ($this->product->price_discounted === null) ? $this->product->price->roubles() : $this->product->price_discounted->roubles();

        $queries = [
            [$basePrice - 100, $basePrice + 100],
            [$basePrice + 100, $basePrice - 100],
            [null, $basePrice + 100],
            [$basePrice - 100, null],
            [null, null],
        ];

        foreach ($queries as [$minPrice, $maxPrice]) {
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::PRICE_BETWEEN->value => "{$minPrice},{$maxPrice}"]]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            if (isset($minPrice, $maxPrice) && $maxPrice < $minPrice) {
                [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
            }

            $this->assertNotEmpty($items);
            $this->assertTrue($items->every(function (array $item) use ($minPrice, $maxPrice): bool {
                $actualPrice = $item['price_discounted'] ?? $item['price'];

                $result = true;
                if (isset($minPrice)) {
                    $result = $actualPrice >= $minPrice;
                }
                if (isset($maxPrice)) {
                    $result = $result && $actualPrice <= $maxPrice;
                }

                return $result;
            }));

            $this->assertCount(1, $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::PRICE_BETWEEN->value));
            $priceBetweenFilter = $appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::PRICE_BETWEEN->value)->first();
            $this->assertEquals($minPrice ?? Product::query()->min(Product::getDatabasePriceExpression()) / Kopecks::KOPECKS_IN_ROUBLE, $priceBetweenFilter['min_value']);
            $this->assertEquals($maxPrice ?? Product::query()->max(Product::getDatabasePriceExpression()) / Kopecks::KOPECKS_IN_ROUBLE, $priceBetweenFilter['max_value']);
        }
    }

    /** @test */
    public function a_user_can_view_specific_product(): void
    {
        $this->get(route('products.show', $this->product))->assertOk();
    }

    /** @test */
    public function a_user_cannot_view_nonexistent_product(): void
    {
        $this->get(route('products.show', 'wrong_product'))->assertNotFound();
    }
}
