<?php

namespace App\Domains\Catalog\Services\Query\Filter;

use App\Components\Purchasable\Models\Price;
use App\Components\Queryable\Classes\Filter\Resources\MultiselectFilter\NestedMultiselectFilterValues;
use App\Components\Queryable\Classes\Filter\Resources\MultiselectFilter\NestedMultiselectFilterValuesAttribute;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductAttribute;
use App\Domains\Catalog\Models\ProductAttributeValue;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

final class ProductFilterBuilderRepository
{
    public function __construct(private readonly CatalogSettings $settings)
    {
    }

    public function getAttributeValues(SpatieQueryBuilder $productsQuery): Collection
    {
        $attributeValuesQuery = DB::table('product_attribute_values')
            ->whereIn('product_id', $productsQuery->getQuery()->select(['products.id']))
            ->join('product_attributes', 'product_attributes.id', 'product_attribute_values.attribute_id')
            ->select(['product_attribute_values.attribute_id', 'product_attribute_values.value_boolean', 'product_attribute_values.value_float', 'product_attribute_values.value_integer', 'product_attribute_values.value_string'])
            ->distinct(['product_attribute_values.attribute_id', 'product_attribute_values.value_boolean', 'product_attribute_values.value_float', 'product_attribute_values.value_integer', 'product_attribute_values.value_string']);

        $attributeIdsQuery = $attributeValuesQuery->clone()->distinct(['product_attribute_values.attribute_id'])->select(['product_attribute_values.attribute_id']);
        $attributeValues = $attributeValuesQuery->get()->groupBy('attribute_id');

        return ProductAttribute::query()
            ->whereIn('id', $attributeIdsQuery)
            ->orderBy('title')
            ->get()
            ->map(static function (ProductAttribute $attribute) use ($attributeValues): NestedMultiselectFilterValues {
                /** @var Collection $values */
                $values = $attributeValues->get($attribute->id);

                return new NestedMultiselectFilterValues(
                    new NestedMultiselectFilterValuesAttribute($attribute->title, $attribute->slug, $attribute->values_type->responseValueType()),
                    $values->pluck(ProductAttributeValue::getDatabaseValueColumnByAttributeType($attribute->values_type))->sort()->values()
                );
            });
    }

    public function getCategories(SpatieQueryBuilder $productsQuery): Collection
    {
        $childCategories = ProductCategory::query()
            ->hasLimitedDepth()
            ->select(['slug', 'title', 'parent_id', 'id'])
            ->whereHas('products', static fn (Builder $query): Builder => $query->whereIn('products.id', $productsQuery->getQuery()->select(['products.id'])))
            ->get();

        $categories = $childCategories->pluck('slug', 'title');

        $checkedParentCategoryIds = collect([]);
        $parentCategoryIds = $childCategories->pluck('parent_id')->unique()->diff($childCategories->pluck('id'));
        while ($parentCategoryIds->isNotEmpty()) {
            $parentCategoryId = $parentCategoryIds->pop();
            if (isset($parentCategoryId) && $checkedParentCategoryIds->doesntContain($parentCategoryId)) {
                $category = ProductCategory::findInHierarchy($parentCategoryId, ProductCategory::getHierarchy());

                if (isset($category)) {
                    $categories->offsetSet($category->title, $category->slug);
                    $parentCategoryIds->push($category->parent_id);
                    $checkedParentCategoryIds->push($category->id);
                }
            }
        }

        return $categories->sort();
    }

    public function getMinPrice(SpatieQueryBuilder $productsQuery, string $currency): ?int
    {
        return DB::table('prices')
            ->where('purchasable_type', Product::class)
            ->whereIn('purchasable_id', $productsQuery->getQuery()->select(['products.id']))
            ->where('currency', $currency)
            ->min(Price::getDatabasePriceExpression());
    }

    public function getMaxPrice(SpatieQueryBuilder $productsQuery, string $currency): ?int
    {
        return DB::table('prices')
            ->where('purchasable_type', Product::class)
            ->whereIn('purchasable_id', $productsQuery->getQuery()->select(['products.id']))
            ->where('currency', $currency)
            ->max(Price::getDatabasePriceExpression());
    }

    public function getAvailableCurrencies(SpatieQueryBuilder $productsQuery): Collection
    {
        return DB::table('prices')
            ->select(['currency'])
            ->where('purchasable_type', Product::class)
            ->whereIn('purchasable_id', $productsQuery->getQuery()->select(['products.id']))
            ->whereIn('currency', $this->settings->available_currencies)
            ->distinct('currency')
            ->pluck('currency')
            ->reduce(fn (Collection $acc, string $currency): Collection => tap($acc, static fn () => $acc->offsetSet(currency($currency)->getName(), $currency)), collect([]))
            ->sort();
    }
}
