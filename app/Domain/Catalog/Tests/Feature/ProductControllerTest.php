<?php

namespace App\Domain\Catalog\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domain\Catalog\Database\Seeders\ProductAttributeSeeder;
use App\Domain\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domain\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domain\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domain\Catalog\Database\Seeders\ProductSeeder;
use App\Domain\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Catalog\Models\Generic\CatalogSettings;
use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductCategory;
use App\Domain\Catalog\Models\ProductPrice;
use App\Domain\Generic\Query\Enums\QueryKey;
use App\Domain\Generic\Response\Enums\ResponseKey;
use Illuminate\Support\Arr;

class ProductControllerTest extends TestCase
{
    private Product $product;
    private CatalogSettings $settings;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Product $product */
        $product = Product::first();

        $this->product = $product;
        $this->settings = app(CatalogSettings::class);
    }

    protected function setUpOnce(): void
    {
        $this->seed([
            ProductCategorySeeder::class,
            ProductAttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
            ProductPriceSeeder::class,
        ]);
    }

    /** @test */
    public function a_user_can_view_products_list(): void
    {
        $this->get(route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)]]))->assertOk();
    }

    /** @test */
    public function a_user_can_filter_products_by_title(): void
    {
        $queries = [$this->product->title, trim(substr($this->product->title, 0, 5))];

        foreach ($queries as $query) {
            $filters = [ProductAllowedFilter::TITLE->value => $query, ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)];
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            $this->assertNotEmpty($items);
            $this->assertTrue($items->every(fn (array $item): bool => str_contains($item['title'], $query)));

            $this->assertCount(count($filters), $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::TITLE->value));
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_description(): void
    {
        $queries = [$this->product->description, trim(substr($this->product->description, 0, 5))];

        foreach ($queries as $query) {
            $filters = [ProductAllowedFilter::DESCRIPTION->value => $query, ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)];
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            $this->assertNotEmpty($items);
            $this->assertTrue($items->every(fn (array $item): bool => str_contains($item['description'], $query)));

            $this->assertCount(count($filters), $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::DESCRIPTION->value));
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_category(): void
    {
        ProductCategory::query()->update(['is_visible' => true]);
        $deepestCategory = ProductCategory::query()->visible()->hasLimitedDepth()->whereHas('products')->where('depth', ProductCategory::MAX_DEPTH)->first();
        $this->assertNotNull($deepestCategory);

        $product = $deepestCategory?->products->first();
        $this->assertNotNull($product);

        $productsCount = Product::query()->count();
        $category = $deepestCategory;
        while (isset($category)) {
            $filters = [ProductAllowedFilter::CATEGORY->value => $category->slug, ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)];
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters, QueryKey::PER_PAGE->value => $productsCount]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            $this->assertNotEmpty($items);
            $this->assertTrue($items->pluck('slug')->contains($product?->slug));

            $this->assertCount(count($filters), $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::CATEGORY->value));
            $this->assertTrue(collect($appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::CATEGORY->value)->first()['values'])->contains($category->slug));

            $category = $category->parent;
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_current_price(): void
    {
        $currency = Arr::first($this->settings->available_currencies);
        /** @var ProductPrice $priceModel */
        $priceModel = $this->product->prices->where('currency', $currency)->first();
        $this->assertNotNull($priceModel);

        $basePrice = ($priceModel->price_discounted === null) ? $priceModel->price->getValue() : $priceModel->price_discounted->getValue();
        $lowestAvailablePrice = money(ProductPrice::query()->where('currency', $currency)->min(ProductPrice::getDatabasePriceExpression()), $currency)->getValue();
        $highestAvailablePrice = money(ProductPrice::query()->where('currency', $currency)->max(ProductPrice::getDatabasePriceExpression()), $currency)->getValue();

        $queries = [
            [max($basePrice - 10, 0.01), $basePrice + 10],
            [$basePrice + 10, max($basePrice - 10, 0.01)],
            [null, $basePrice + 10],
            [max($basePrice - 10, 0.01), null],
            [null, null],
        ];

        foreach ($queries as [$minPrice, $maxPrice]) {
            $filters = [ProductAllowedFilter::PRICE_BETWEEN->value => "{$minPrice},{$maxPrice}", ProductAllowedFilter::CURRENCY->value => $currency];
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            if (isset($minPrice, $maxPrice) && $maxPrice < $minPrice) {
                [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
            }

            $this->assertNotEmpty($items);
            $this->assertTrue($items->every(function (array $item) use ($minPrice, $maxPrice): bool {
                $actualPrice = (float) (str_replace($item['currency'], '', $item['price_discounted'] ?? $item['price']));

                $result = true;
                if (isset($minPrice)) {
                    $result = $actualPrice >= $minPrice;
                }
                if (isset($maxPrice)) {
                    $result = $result && $actualPrice <= $maxPrice;
                }

                return $result;
            }));

            $this->assertCount(count($filters), $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::PRICE_BETWEEN->value));

            $priceBetweenFilter = $appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::PRICE_BETWEEN->value)->first();
            $this->assertEquals(isset($minPrice) ? max($minPrice, $lowestAvailablePrice) : $lowestAvailablePrice, $priceBetweenFilter['min_value']);
            $this->assertEquals(isset($maxPrice) ? min($maxPrice, $highestAvailablePrice) : $highestAvailablePrice, $priceBetweenFilter['max_value']);
        }
    }

    /** @test */
    public function a_user_can_view_specific_product_if_it_has_at_least_one_visible_category(): void
    {
        ProductCategory::query()->update(['is_visible' => true]);
        $this->get(route('products.show', [$this->product, QueryKey::FILTER->value => [ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)]]))->assertOk();
    }

    /** @test */
    public function a_user_cannot_view_nonexistent_product(): void
    {
        $this->get(route('products.show', ['wrong_product', QueryKey::FILTER->value => [ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)]]))->assertNotFound();
    }

    /** @test */
    public function a_user_cannot_view_specific_product_if_it_doesnt_have_at_least_one_visible_category(): void
    {
        $setVisibility = static function (ProductCategory $category, bool $isVisible): void {
            $category->is_visible = $isVisible;
            $category->save();
        };

        $setProductCategory = static function (Product $product, ProductCategory $category): void {
            $product->categories()->sync([$category->id]);
        };

        /** @var ProductCategory $rootCategory */
        $rootCategory = ProductCategory::query()->where('depth', 0)->first();
        $this->assertNotNull($rootCategory);

        /** @var ProductCategory $firstLevelCategory */
        $firstLevelCategory = ProductCategory::query()->where('depth', 1)->first();
        $this->assertNotNull($firstLevelCategory);

        $firstLevelCategory->parent()->associate($rootCategory);
        $firstLevelCategory->save();

        $setVisibility($rootCategory, false);
        $setVisibility($firstLevelCategory, false);

        $setProductCategory($this->product, $firstLevelCategory);

        $this->get(route('products.show', [$this->product, QueryKey::FILTER->value => [ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)]]))->assertNotFound();

        $setVisibility($firstLevelCategory, true);

        $this->get(route('products.show', [$this->product, QueryKey::FILTER->value => [ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)]]))->assertNotFound();

        $setVisibility($rootCategory, true);

        $this->get(route('products.show', [$this->product, QueryKey::FILTER->value => [ProductAllowedFilter::CURRENCY->value => Arr::first($this->settings->available_currencies)]]))->assertOk();
    }
}
