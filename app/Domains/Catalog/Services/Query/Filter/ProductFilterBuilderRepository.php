<?php

namespace App\Domains\Catalog\Services\Query\Filter;

use App\Components\Attributable\Models\Attribute;
use App\Components\Attributable\Models\AttributeValue;
use App\Components\Purchasable\Models\Price;
use App\Components\Queryable\Classes\Filter\Multiselect\Resources\NestedMultiselectFilterValues;
use App\Components\Queryable\Classes\Filter\Multiselect\Resources\NestedMultiselectFilterValuesAttribute;
use App\Domains\Catalog\Database\Builders\ProductBuilder;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        $attributeValuesQuery = DB::table('attribute_values')
            ->where('attributable_type', Product::class)
            ->whereIn('attributable_id', $productsQuery->getQuery()->select(['products.id']))
            ->join('attributes', 'attributes.id', 'attribute_values.attribute_id')
            ->select(['attribute_values.attribute_id', 'attribute_values.value_boolean', 'attribute_values.value_float', 'attribute_values.value_integer', 'attribute_values.value_string'])
            ->distinct(['attribute_values.attribute_id', 'attribute_values.value_boolean', 'attribute_values.value_float', 'attribute_values.value_integer', 'attribute_values.value_string']);

        $attributeIdsQuery = $attributeValuesQuery->clone()->distinct(['attribute_values.attribute_id'])->select(['attribute_values.attribute_id']);
        $attributeValues = $attributeValuesQuery->get()->groupBy('attribute_id');

        return Attribute::query()
            ->whereIn('id', $attributeIdsQuery)
            ->orderBy('title')
            ->get()
            ->map(static function (Attribute $attribute) use ($attributeValues): NestedMultiselectFilterValues {
                /** @var Collection $values */
                $values = $attributeValues->get($attribute->id);

                return new NestedMultiselectFilterValues(
                    new NestedMultiselectFilterValuesAttribute($attribute->title, $attribute->slug, $attribute->values_type->responseValueType()),
                    $values->pluck(AttributeValue::getDatabaseValueColumnByAttributeType($attribute->values_type))->sort()->values()
                );
            });
    }

    public function getCategories(SpatieQueryBuilder $productsQuery): Collection
    {
        $childCategories = ProductCategory::query()
            ->hasLimitedDepth()
            ->select(['slug', 'title', 'parent_id', 'id'])
            ->whereHas('products', static fn (ProductBuilder $query) => $query->whereIn('products.id', $productsQuery->getQuery()->select(['products.id'])))
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
            ->where('purchasable_type', Relation::getAlias(Product::class))
            ->whereIn('purchasable_id', $productsQuery->getQuery()->select(['products.id']))
            ->where('currency', $currency)
            ->min(Price::getDatabasePriceExpression());
    }

    public function getMaxPrice(SpatieQueryBuilder $productsQuery, string $currency): ?int
    {
        return DB::table('prices')
            ->where('purchasable_type', Relation::getAlias(Product::class))
            ->whereIn('purchasable_id', $productsQuery->getQuery()->select(['products.id']))
            ->where('currency', $currency)
            ->max(Price::getDatabasePriceExpression());
    }

    public function getAvailableCurrencies(SpatieQueryBuilder $productsQuery): Collection
    {
        return DB::table('prices')
            ->select(['currency'])
            ->where('purchasable_type', Relation::getAlias(Product::class))
            ->whereIn('purchasable_id', $productsQuery->getQuery()->select(['products.id']))
            ->whereIn('currency', $this->settings->available_currencies)
            ->distinct('currency')
            ->pluck('currency')
            ->reduce(fn (Collection $acc, string $currency): Collection => tap($acc, static fn () => $acc->offsetSet(currency($currency)->getName(), $currency)), collect([]))
            ->sort();
    }
}
