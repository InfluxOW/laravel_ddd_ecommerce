<?php

namespace App\Domains\Catalog\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Components\Generic\Enums\Response\ResponseKey;
use App\Components\Generic\Utils\StringUtils;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Database\Seeders\ProductAttributeSeeder;
use App\Domains\Catalog\Database\Seeders\ProductAttributeValueSeeder;
use App\Domains\Catalog\Database\Seeders\ProductCategorySeeder;
use App\Domains\Catalog\Database\Seeders\ProductPriceSeeder;
use App\Domains\Catalog\Database\Seeders\ProductSeeder;
use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductAttributeValue;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Models\ProductPrice;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Illuminate\Database\Eloquent\Builder;

class ProductControllerTest extends TestCase
{
    private Product $product;
    private CatalogSettings $settings;

    protected static bool $doNotRecreateDatabase = true;

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

        ProductCategory::query()->update(['is_visible' => true]);

        ProductCategory::loadHierarchy();
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
            $filters = [ProductAllowedFilter::TITLE->name => $query];
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            $this->assertNotEmpty($items);
            $items->each(fn (array $item) => $this->assertTrue(str_contains($item['title'], $query)));

            $this->assertCount(count($filters) + 1, $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::TITLE->name));
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_description(): void
    {
        $queries = [$this->product->description, trim(substr($this->product->description, 0, 5))];

        foreach ($queries as $query) {
            $filters = [ProductAllowedFilter::DESCRIPTION->name => $query];
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            $this->assertNotEmpty($items);
            $items->each(fn (array $item) => $this->assertTrue(str_contains($this->get($item['url'])->json(ResponseKey::DATA->value)['description'], $query)));

            $this->assertCount(count($filters) + 1, $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::DESCRIPTION->name));
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_categories(): void
    {
        $deepestCategory = ProductCategory::query()->visible()->hasLimitedDepth()->whereHas('products')->where('depth', ProductCategory::MAX_DEPTH)->first();
        $this->assertNotNull($deepestCategory);

        $product = $deepestCategory?->products->first();
        $this->assertNotNull($product);

        $productsCount = Product::query()->count();
        $category = $deepestCategory;
        $query = [];
        while (isset($category)) {
            $query[] = $category->slug;

            foreach ([$category->slug, implode(',', $query)] as $categoriesQuery) {
                $filters = [ProductAllowedFilter::CATEGORY->name => $categoriesQuery];
                $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters, QueryKey::PER_PAGE->value => $productsCount]))->assertOk();
                $items = collect($response->json(ResponseKey::DATA->value));
                $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

                $this->assertNotEmpty($items);
                $this->assertTrue($items->pluck('slug')->contains($product?->slug));

                $this->assertCount(count($filters) + 1, $appliedFilters);
                $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::CATEGORY->name));

                $categoryFilterValues = collect($appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::CATEGORY->name)->first()['selected_values']);
                if ($categoriesQuery === $category->slug) {
                    $this->assertTrue($categoryFilterValues->contains($category->slug));
                } else {
                    $this->assertEqualsCanonicalizing($query, $categoryFilterValues->toArray());
                }
            }

            $category = $category->parent;
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_current_price(): void
    {
        $currency = $this->settings->default_currency;
        /** @var ProductPrice $priceModel */
        $priceModel = $this->product->prices->where('currency', $currency)->first();
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
            $filters = [ProductAllowedFilter::PRICE_BETWEEN->name => "{$minPrice},{$maxPrice}"];
            $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters]))->assertOk();
            $items = collect($response->json(ResponseKey::DATA->value));
            $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

            if (isset($minPrice, $maxPrice) && $maxPrice < $minPrice) {
                [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
            }

            $priceQuery = ProductPrice::query()->where('currency', $currency);

            $minPriceQuery = $priceQuery->clone();
            $maxPriceQuery = $priceQuery->clone();
            if (isset($minPrice)) {
                $minPriceQuery->where(ProductPrice::getDatabasePriceExpression(), '>=', money($minPrice, $currency, true)->getAmount());
            }
            if (isset($maxPrice)) {
                $maxPriceQuery->where(ProductPrice::getDatabasePriceExpression(), '<=', money($maxPrice, $currency, true)->getAmount());
            }
            $lowestAvailablePrice = money($minPriceQuery->min(ProductPrice::getDatabasePriceExpression()), $currency)->getValue();
            $highestAvailablePrice = money($maxPriceQuery->max(ProductPrice::getDatabasePriceExpression()), $currency)->getValue();

            $this->assertNotEmpty($items);
            $items->each(function (array $item) use ($minPrice, $maxPrice): void {
                $actualPrice = $item['price_discounted']['value'] ?? $item['price']['value'];

                $result = true;
                if (isset($minPrice)) {
                    $result = $actualPrice >= $minPrice;
                }
                if (isset($maxPrice)) {
                    $result = $result && $actualPrice <= $maxPrice;
                }

                $this->assertTrue($result);
            });

            $this->assertCount(count($filters) + 1, $appliedFilters);
            $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::PRICE_BETWEEN->name));

            $priceBetweenFilter = $appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::PRICE_BETWEEN->name)->first();

            $this->assertEquals(isset($minPrice) ? max($minPrice, $lowestAvailablePrice) : $lowestAvailablePrice, $priceBetweenFilter['min_value']);
            $this->assertEquals(isset($maxPrice) ? min($maxPrice, $highestAvailablePrice) : $highestAvailablePrice, $priceBetweenFilter['max_value']);
        }
    }

    /** @test */
    public function a_user_can_filter_products_by_attribute_values(): void
    {
        /** @var Product $firstProduct */
        $firstProduct = Product::query()->with(['attributeValues.attribute'])->whereHas('attributeValues', null, '>', 1)->inRandomOrder()->first();
        $this->assertNotNull($firstProduct);

        $originalValueToString = static fn (mixed $originalValue, ProductAttributeValuesType $valueType): string => match ($valueType) {
            ProductAttributeValuesType::BOOLEAN => StringUtils::boolToString($originalValue),
            ProductAttributeValuesType::INTEGER, ProductAttributeValuesType::STRING, ProductAttributeValuesType::FLOAT => (string) $originalValue,
        };

        /**
         * @var ProductAttributeValue $firstAttributeValue
         * @var ProductAttributeValue $secondAttributeValue
         */
        [$firstAttributeValue, $secondAttributeValue] = $firstProduct->attributeValues;

        $firstAttribute = $firstAttributeValue->attribute;
        $firstAttributeFirstValueOriginal = $firstAttributeValue->value;
        $firstAttributeSecondValueOriginal = ProductAttributeValue::query()->whereBelongsTo($firstAttribute, 'attribute')->where(ProductAttributeValue::getDatabaseValueColumnByAttributeType($firstAttribute->values_type), '<>', $firstAttributeFirstValueOriginal)->inRandomOrder()->first()?->value;

        $firstAttributeFirstValue = $originalValueToString($firstAttributeFirstValueOriginal, $firstAttribute->values_type);
        $firstAttributeSecondValue = $originalValueToString($firstAttributeSecondValueOriginal, $firstAttribute->values_type);

        $secondAttribute = $secondAttributeValue->attribute;
        $secondAttributeFirstValueOriginal = $secondAttributeValue->value;

        /** @var Product $secondProduct */
        $secondProduct = Product::query()
            ->with(['attributeValues.attribute'])
            ->whereHas('attributeValues', fn (Builder $query): Builder => $query->where(ProductAttributeValue::getDatabaseValueColumnByAttributeType($firstAttribute->values_type), $firstAttributeSecondValueOriginal))
            ->first();
        $this->assertNotNull($secondProduct);

        /** @var ProductAttributeValue $attributeValueThatAddsSecondProductToTheSearch */
        $attributeValueThatAddsSecondProductToTheSearch = $secondProduct->attributeValues->where('attribute.id', $secondAttribute->id)->isNotEmpty() ? $secondProduct->attributeValues->where('attribute.id', $secondAttribute->id)->first() : $secondProduct->attributeValues()->make();
        $attributeValueThatAddsSecondProductToTheSearch->attribute()->associate($secondAttribute);
        $attributeValueThatAddsSecondProductToTheSearch->value = $secondAttributeFirstValueOriginal;
        $attributeValueThatAddsSecondProductToTheSearch->save();

        $secondAttributeFirstValue = $originalValueToString($secondAttributeFirstValueOriginal, $secondAttribute->values_type);

        /*
         * It should find products that both have first and second attributes,
         * but first attribute may have one of 2 available values while second's
         * attribute value is locked to 1 available value.
         * */
        $query = [
            $firstAttribute->slug => implode(',', [$firstAttributeFirstValue, $firstAttributeSecondValue]),
            $secondAttribute->slug => $secondAttributeFirstValue,
        ];

        $filters = [ProductAllowedFilter::ATTRIBUTE_VALUE->name => $query];
        $response = $this->get(route('products.index', [QueryKey::FILTER->value => $filters, QueryKey::PER_PAGE->value => Product::query()->count()]))->assertOk();
        $items = collect($response->json(ResponseKey::DATA->value));
        $appliedFilters = collect($response->json(sprintf('%s.%s.%s', ResponseKey::QUERY->value, QueryKey::FILTER->value, 'applied')));

        $this->assertNotEmpty($items);
        $this->assertCount(2, $items);
        $items->each(function (array $item) use ($firstAttribute, $secondAttribute, $firstAttributeFirstValueOriginal, $firstAttributeSecondValueOriginal, $secondAttributeFirstValueOriginal): void {
            $item = $this->get($item['url'])->json(ResponseKey::DATA->value);
            $attributes = collect($item['attributes']);

            $this->assertEquals($attributes->where('attribute.slug', $secondAttribute->slug)->first()['value'], $secondAttributeFirstValueOriginal);
            $this->assertContains($attributes->where('attribute.slug', $firstAttribute->slug)->first()['value'], [$firstAttributeFirstValueOriginal, $firstAttributeSecondValueOriginal]);
        });

        $this->assertCount(count($filters) + 1, $appliedFilters);
        $this->assertTrue($appliedFilters->pluck('query')->contains(ProductAllowedFilter::ATTRIBUTE_VALUE->name));

        $attributeValuesFilterValues = collect($appliedFilters->filter(fn (array $filter): bool => $filter['query'] === ProductAllowedFilter::ATTRIBUTE_VALUE->name)->first()['selected_values']);
        $this->assertEqualsCanonicalizing([$firstAttributeFirstValue, $firstAttributeSecondValue], $attributeValuesFilterValues->where('attribute.query', $firstAttribute->slug)->first()['values']);
        $this->assertEqualsCanonicalizing([$secondAttributeFirstValue], $attributeValuesFilterValues->where('attribute.query', $secondAttribute->slug)->first()['values']);
    }

    /** @test */
    public function a_user_cannot_view_nonexistent_product(): void
    {
        $this->get(route('products.show', 'wrong_product'))->assertNotFound();
    }

    /** @test */
    public function a_user_can_view_specific_product_if_it_has_at_least_one_visible_category(): void
    {
        $this->get(route('products.show', $this->product))->assertOk();
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

        $this->get(route('products.show', $this->product))->assertNotFound();
        $this->assertFalse(ProductCategory::query()->where('product_categories.id', $firstLevelCategory->id)->visible()->exists());

        $setVisibility($firstLevelCategory, true);

        $this->get(route('products.show', $this->product))->assertNotFound();
        $this->assertFalse(ProductCategory::query()->where('product_categories.id', $firstLevelCategory->id)->visible()->exists());

        $setVisibility($rootCategory, true);

        $this->get(route('products.show', $this->product))->assertOk();
        $this->assertTrue(ProductCategory::query()->where('product_categories.id', $firstLevelCategory->id)->visible()->exists());
    }
}
