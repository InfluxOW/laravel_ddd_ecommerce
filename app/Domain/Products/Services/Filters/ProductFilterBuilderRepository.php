<?php

namespace App\Domain\Products\Services\Filters;

use App\Domain\Products\Models\Generic\Filters\Resources\Multiselect\MultiselectFilterNestedValues;
use App\Domain\Products\Models\Generic\Filters\Resources\Multiselect\MultiselectFilterNestedValuesAttribute;
use App\Domain\Products\Models\ProductAttribute;
use App\Domain\Products\Models\ProductAttributeValue;
use App\Domain\Products\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

class ProductFilterBuilderRepository
{
    public function getAttributeValues(SpatieQueryBuilder $productsQuery): Collection
    {
        $attributeValuesQuery = DB::table('product_attribute_values')
            ->whereIn('product_id', $productsQuery->getQuery()->select(['id']))
            ->join('product_attributes', 'product_attributes.id', 'product_attribute_values.attribute_id')
            ->select(['product_attribute_values.attribute_id', 'product_attribute_values.value_boolean', 'product_attribute_values.value_float', 'product_attribute_values.value_integer', 'product_attribute_values.value_string'])
            ->distinct(['product_attribute_values.attribute_id', 'product_attribute_values.value_boolean', 'product_attribute_values.value_float', 'product_attribute_values.value_integer', 'product_attribute_values.value_string']);

        $attributeIdsQuery = $attributeValuesQuery->clone()->distinct(['product_attribute_values.attribute_id'])->select(['product_attribute_values.attribute_id']);
        $attributeValues = $attributeValuesQuery->get()->groupBy('attribute_id');

        return ProductAttribute::query()
            ->whereIn('id', $attributeIdsQuery)
            ->orderBy('title')
            ->get()
            ->map(static function (ProductAttribute $attribute) use ($attributeValues): MultiselectFilterNestedValues {
                /** @var Collection $values */
                $values = $attributeValues->get($attribute->id);

                return new MultiselectFilterNestedValues(
                    new MultiselectFilterNestedValuesAttribute($attribute->title, $attribute->slug),
                    $values->pluck(ProductAttributeValue::getDatabaseValueColumnByAttributeType($attribute->type))->sort()->values()
                );
            });
    }

    public function getCategories(SpatieQueryBuilder $productsQuery): Collection
    {
        return ProductCategory::query()
            ->select(['slug', 'title'])
            ->whereHas('products', static fn (Builder $query): Builder => $query->whereIn('id', $productsQuery->getQuery()->select(['id'])))
            ->pluck('slug', 'title');
    }

    public function getMinPrice(SpatieQueryBuilder $productsQuery): ?int
    {
        return $productsQuery->min(DB::raw('COALESCE(price_discounted, price)'));
    }

    public function getMaxPrice(SpatieQueryBuilder $productsQuery): ?int
    {
        return $productsQuery->max(DB::raw('COALESCE(price_discounted, price)'));
    }
}
