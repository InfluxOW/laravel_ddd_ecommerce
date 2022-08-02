<?php

namespace App\Domains\Catalog\Tests\Feature\Api;

use App\Application\Tests\TestCase;
use App\Components\Attributable\Database\Seeders\AttributeSeeder;
use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Models\AttributeValue;
use App\Components\Purchasable\Models\Price;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Console\Commands\UpdateProductCategoriesDisplayability;
use App\Domains\Catalog\Console\Commands\UpdateProductsDisplayability;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Enums\Query\Sort\ProductAllowedSort;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Domains\Generic\Enums\Response\ResponseKey;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

final class ProductControllerTest extends TestCase
{
    private static Product $product;

    private static CatalogSettings $settings;

    protected function setUpOnce(): void
    {
        $this->seed([
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ProductPriceSeeder::class,
            AttributeSeeder::class,
            ProductAttributeValueSeeder::class,
        ]);

        ProductCategory::query()->update(['is_visible' => true, 'is_displayable' => true]);
        Product::query()->update(['is_visible' => true, 'is_displayable' => true]);

        ProductCategory::loadHierarchy();

        /** @var Product $product */
        $product = Product::query()->with(['prices'])->first();

        self::$product = $product;
        self::$settings = app(CatalogSettings::class);
    }

    /** @test */
    public function a_user_can_view_products_list(): void
    {
        $this->get(route('products.index'))->assertOk();
    }

    /**
     * @test
     * @unstable
     */
    public function a_user_can_search_products_by_title_or_description(): void
    {
        $this->refreshModelIndex(Product::class);

        $queries = [
            self::$product->title, Str::words(self::$product->title, 2, ''),
            self::$product->description, Str::words(self::$product->description, 2, ''),
        ];

        $productsCount = Product::query()->count();
        foreach ($queries as $query) {
            $response = $this->get(
                route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::SEARCH->name => $query], QueryKey::PER_PAGE->value => $productsCount])
            )->assertOk();

            $this->assertContains(self::$product->title, $this->getResponseData($response)->pluck('title'));
            $this->assertContains(ProductAllowedFilter::SEARCH->name, $this->getResponseAppliedFilters($response)->pluck('query'));
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_categories(): void
    {
        /** @var ProductCategory $deepestCategory */
        $deepestCategory = ProductCategory::query()->displayable()->whereHas('products')->where('depth', ProductCategory::MAX_DEPTH)->first();
        $this->assertNotNull($deepestCategory);

        /** @var Product $product */
        $product = $deepestCategory->products->first();
        $this->assertNotNull($product);

        $productsCount = Product::query()->count();
        $category = $deepestCategory;
        $query = [];
        while (isset($category)) {
            $query[] = $category->slug;

            foreach ([$category->slug, implode(',', $query)] as $categoriesQuery) {
                $response = $this->get(
                    route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::CATEGORY->name => $categoriesQuery], QueryKey::PER_PAGE->value => $productsCount])
                )->assertOk();
                $appliedFilters = $this->getResponseAppliedFilters($response);

                $this->assertContains($product->title, $this->getResponseData($response)->pluck('title'));
                $this->assertContains(ProductAllowedFilter::CATEGORY->name, $appliedFilters->pluck('query'));

                $categoryFilterValues = collect($appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::CATEGORY->name)->first()['selected']);
                if ($categoriesQuery === $category->slug) {
                    $this->assertContains($category->slug, $categoryFilterValues);
                } else {
                    $this->assertEqualsCanonicalizing($query, $categoryFilterValues->toArray());
                }
            }

            $category = $category->parent;
        }
    }

    /**
     * @test
     * @complicated
     */
    public function a_user_can_filter_products_by_current_price(): void
    {
        $currency = self::$settings->default_currency;
        /** @var Price $priceModel */
        $priceModel = self::$product->prices->where('currency', $currency)->first();
        $this->assertNotNull($priceModel);

        $basePrice = ($priceModel->price_discounted === null) ? $priceModel->price->getValue() : $priceModel->price_discounted->getValue();

        $queries = [
            [max($basePrice - 10, 0.01), $basePrice + 10],
            [$basePrice + 10, max($basePrice - 10, 0.01)],
            [null, $basePrice + 10],
            [max($basePrice - 10, 0.01), null],
            [null, null],
        ];

        foreach ($queries as [$minPrice, $maxPrice]) {
            $response = $this->get(
                route('products.index', [QueryKey::FILTER->value => [ProductAllowedFilter::PRICE_BETWEEN->name => "{$minPrice},{$maxPrice}"]])
            )->assertOk();
            $products = $this->getResponseData($response);
            $appliedFilters = $this->getResponseAppliedFilters($response);
            $allowedFilters = $this->getResponseAllowedFilters($response);

            if (isset($minPrice, $maxPrice) && $maxPrice < $minPrice) {
                [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
            }

            $this->assertNotEmpty($products);
            $products->each(function (array $product) use ($minPrice, $maxPrice): void {
                $actualPrice = $product['price_discounted']['value'] ?? $product['price']['value'];

                $result = true;
                if (isset($minPrice)) {
                    $result = $actualPrice >= $minPrice;
                }
                if (isset($maxPrice)) {
                    $result = $result && $actualPrice <= $maxPrice;
                }

                $this->assertTrue($result);
            });

            $this->assertContains(ProductAllowedFilter::PRICE_BETWEEN->name, $appliedFilters->pluck('query'));

            $priceBetweenAppliedFilter = $appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::PRICE_BETWEEN->name)->first();
            $priceBetweenAllowedFilter = $allowedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::PRICE_BETWEEN->name)->first();

            $lowestAvailablePrice = $priceBetweenAllowedFilter['min'];
            $highestAvailablePrice = $priceBetweenAllowedFilter['max'];

            $this->assertEquals(isset($minPrice) ? max($minPrice, $lowestAvailablePrice) : $lowestAvailablePrice, $priceBetweenAppliedFilter['min']);
            $this->assertEquals(isset($maxPrice) ? min($maxPrice, $highestAvailablePrice) : $highestAvailablePrice, $priceBetweenAppliedFilter['max']);
        }
    }

    /**
     * @test
     * @complicated
     * @unstable
     */
    public function a_user_can_filter_products_by_attribute_values(): void
    {
        $nonBooleanAttributeValuesQuery = static fn (Builder|MorphMany $query) => $query->whereHas('attribute', fn (Builder|MorphMany $query) => $query->whereIn('values_type', [AttributeValuesType::INTEGER, AttributeValuesType::STRING, AttributeValuesType::FLOAT]));

        /** @var Product $teapot */
        $teapot = Product::query()
            ->with([
                'attributeValues' => $nonBooleanAttributeValuesQuery,
                'attributeValues.attribute',
            ])
            ->whereHas('attributeValues', $nonBooleanAttributeValuesQuery, '>', 1)
            ->inRandomOrder()
            ->first();
        $this->assertNotNull($teapot);

        /**
         * @var AttributeValue $heightAttributeValue
         * @var AttributeValue $widthAttributeValue
         */
        [$heightAttributeValue, $widthAttributeValue] = $teapot->attributeValues;

        $height = $heightAttributeValue->attribute;
        $heightSmallValue = $heightAttributeValue->value;
        /** @var float|int|string $heightGreatValue */
        $heightGreatValue = AttributeValue::query()
            ->whereBelongsTo($height, 'attribute')
            ->where(AttributeValue::getDatabaseValueColumnByAttributeType($height->values_type), '<>', $heightSmallValue)
            ->inRandomOrder()
            ->first()
            ?->value;
        $this->assertNotNull($heightGreatValue);

        $width = $widthAttributeValue->attribute;
        $widthValue = $widthAttributeValue->value;

        /** @var Product $tv */
        $tv = Product::query()
            ->with(['attributeValues.attribute'])
            ->whereHas('attributeValues', fn (Builder $query): Builder => $query->where(AttributeValue::getDatabaseValueColumnByAttributeType($height->values_type), $heightGreatValue))
            ->first();
        $this->assertNotNull($tv);

        /** @var AttributeValue $tvWidthAttributeValue */
        $tvWidthAttributeValue = $tv->attributeValues->where('attribute.id', $width->id)->isNotEmpty() ? $tv->attributeValues->where('attribute.id', $width->id)->first() : $tv->attributeValues()->make();
        $tvWidthAttributeValue->attribute()->associate($width);
        $tvWidthAttributeValue->value = $widthValue;
        $tvWidthAttributeValue->save();

        $query = [
            $height->slug => implode(',', [$heightSmallValue, $heightGreatValue]),
            $width->slug => $widthValue,
        ];

        $filters = [ProductAllowedFilter::ATTRIBUTE_VALUE->name => $query];
        $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters, QueryKey::PER_PAGE->value => Product::query()->count()]))->assertOk();
        $products = $this->getResponseData($response);
        $appliedFilters = $this->getResponseAppliedFilters($response);

        $this->assertCount(2, $products);
        $products->each(function (array $item) use ($height, $width, $heightSmallValue, $heightGreatValue, $widthValue): void {
            $item = $this->getResponseData($this->get($item['url']));
            $attributes = collect($item['attributes']);

            $this->assertEquals($attributes->where('attribute.slug', $width->slug)->first()['value'], $widthValue);
            $this->assertContains($attributes->where('attribute.slug', $height->slug)->first()['value'], [$heightSmallValue, $heightGreatValue]);
        });

        $this->assertContains(ProductAllowedFilter::ATTRIBUTE_VALUE->name, $appliedFilters->pluck('query'));

        $attributeValuesFilterValues = collect($appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::ATTRIBUTE_VALUE->name)->first()['selected']);
        $this->assertEqualsCanonicalizing([$heightSmallValue, $heightGreatValue], $attributeValuesFilterValues->where('attribute.query', $height->slug)->first()['values']);
        $this->assertEqualsCanonicalizing([$widthValue], $attributeValuesFilterValues->where('attribute.query', $width->slug)->first()['values']);
    }

    /** @test */
    public function a_user_cannot_view_nonexistent_product(): void
    {
        $this->get(route('products.show', 'wrong_product'))->assertNotFound();
    }

    /** @test */
    public function a_user_can_view_specific_product_if_it_has_at_least_one_visible_category(): void
    {
        $this->get(route('products.show', self::$product))->assertOk();
    }

    /** @test */
    public function a_user_cannot_view_specific_product_if_it_doesnt_have_at_least_one_visible_category(): void
    {
        $setVisibility = function (ProductCategory $category, bool $isVisible): void {
            $category->is_visible = $isVisible;
            $category->save();

            $this->artisan(UpdateProductCategoriesDisplayability::class);
            $this->artisan(UpdateProductsDisplayability::class);
        };

        $setProductCategory = function (Product $product, ProductCategory $category): void {
            $product->categories()->sync([$category->id]);

            $this->artisan(UpdateProductCategoriesDisplayability::class);
            $this->artisan(UpdateProductsDisplayability::class);
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

        $setProductCategory(self::$product, $firstLevelCategory);

        $this->get(route('products.show', self::$product))->assertNotFound();

        $setVisibility($firstLevelCategory, true);

        $this->get(route('products.show', self::$product))->assertNotFound();

        $setVisibility($rootCategory, true);

        $this->get(route('products.show', self::$product))->assertOk();
    }

    /** @test */
    public function a_user_cannot_view_specific_product_if_it_doesnt_have_prices_with_all_required_currencies(): void
    {
        /** @var Price $price */
        $price = self::$product->prices->first();
        $validCurrency = $price->currency;

        $updateCurrency = function (string $currency) use ($price): void {
            $price->currency = $currency;
            $price->save();

            $this->artisan(UpdateProductsDisplayability::class);
        };

        $this->get(route('products.show', self::$product))->assertOk();

        $updateCurrency((string) random_int(100, 999));

        $this->get(route('products.show', self::$product))->assertNotFound();

        $updateCurrency($validCurrency);

        $this->get(route('products.show', self::$product))->assertOk();
    }

    /** @test */
    public function a_user_cannot_view_specific_product_if_it_isnt_marked_as_visible(): void
    {
        $updateVisibility = function (bool $isVisible): void {
            self::$product->is_visible = $isVisible;
            self::$product->save();

            $this->artisan(UpdateProductsDisplayability::class);
        };

        $this->get(route('products.show', self::$product))->assertOk();

        $updateVisibility(false);

        $this->get(route('products.show', self::$product))->assertNotFound();

        $updateVisibility(true);

        $this->get(route('products.show', self::$product))->assertOk();
    }

    /**
     * @test
     */
    public function a_user_can_sort_products_by_title_a_to_z(): void
    {
        $this->checkProductsSort(ProductAllowedSort::TITLE);
    }

    /** @test */
    public function a_user_can_sort_products_by_title_z_to_a(): void
    {
        $this->checkProductsSort(ProductAllowedSort::TITLE_DESC);
    }

    /** @test */
    public function a_user_can_sort_products_by_newest_first(): void
    {
        $this->checkProductsSort(ProductAllowedSort::CREATED_AT);
    }

    /** @test */
    public function a_user_can_sort_products_by_oldest_first(): void
    {
        $this->checkProductsSort(ProductAllowedSort::CREATED_AT_DESC);
    }

    /** @test */
    public function a_user_can_sort_products_by_expensive_first(): void
    {
        $this->checkProductsSort(ProductAllowedSort::PRICE);
    }

    /** @test */
    public function a_user_can_sort_products_by_cheapest_first(): void
    {
        $this->checkProductsSort(ProductAllowedSort::PRICE_DESC);
    }

    private function checkProductsSort(ProductAllowedSort $sort): void
    {
        $products = $this->getProductsSortedBy($sort);

        $this->assertEquals(
            $products->pluck($sort->getDatabaseField()),
            $products->sortBy(...$this->getSortParametersByType($sort))->pluck($sort->getDatabaseField())
        );
    }

    private function getProductsSortedBy(ProductAllowedSort $sort): Collection
    {
        $response = $this->get(
            route('products.index', [QueryKey::SORT->value => $sort->name, QueryKey::PER_PAGE->value => Product::query()->count()])
        )->assertOk();
        $products = $this->getResponseData($response);
        $appliedSort = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::SORT->value, 'applied')));

        $this->assertEquals($appliedSort['query'], $sort->name);

        return $products;
    }

    private function getSortParametersByType(ProductAllowedSort $sort): array
    {
        $titleSort = static fn (array $product): string => $product['title'];
        /** @phpstan-ignore-next-line */
        $createdAtSort = static fn (array $product): int => (int) Carbon::createFromFormat(DateTime::RFC3339, $product['created_at'])->timestamp;
        $priceSort = static fn (array $product): int => $product['price_discounted']['amount'] ?? $product['price']['amount'];

        return match ($sort) {
            ProductAllowedSort::TITLE => [$titleSort, SORT_STRING, false],
            ProductAllowedSort::TITLE_DESC => [$titleSort, SORT_STRING, true],
            ProductAllowedSort::CREATED_AT => [$createdAtSort, SORT_NUMERIC, false],
            ProductAllowedSort::CREATED_AT_DESC => [$createdAtSort, SORT_NUMERIC, true],
            ProductAllowedSort::PRICE => [$priceSort, SORT_NUMERIC, false],
            ProductAllowedSort::PRICE_DESC => [$priceSort, SORT_NUMERIC, true],
            ProductAllowedSort::DEFAULT => throw new Exception('To be implemented'),
        };
    }

    private function getResponseData(TestResponse $response): Collection
    {
        return collect($response->json(ResponseKey::DATA->value));
    }

    private function getResponseAppliedFilters(TestResponse $response): Collection
    {
        return collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));
    }

    private function getResponseAllowedFilters(TestResponse $response): Collection
    {
        return collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'allowed')));
    }
}
