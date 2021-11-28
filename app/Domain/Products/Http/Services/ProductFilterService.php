<?php

namespace App\Domain\Products\Http\Services;

use App\Domain\Products\Enums\FrontendFilterType;
use App\Domain\Products\Enums\ProductAllowedFilter;
use App\Domain\Products\Enums\ProductAllowedSort;
use App\Domain\Products\Http\Requests\ProductIndexRequest;
use App\Domain\Products\Http\Resources\ProductAttributeResource;
use App\Domain\Products\Models\Generic\Kopecks;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Models\ProductAttribute;
use App\Domain\Products\Models\ProductAttributeValue;
use App\Domain\Products\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

class ProductFilterService
{
    public function getAvailableFilters(SpatieQueryBuilder $productsQuery): array
    {
        return [
            'allowed_filters' => $this->getAllowedFilters($productsQuery),
            'allowed_sorts' => $this->getAllowedSorts(),
        ];
    }

    public function getAppliedFilters(ProductIndexRequest $request, string $defaultSort): array
    {
        /** @var string $querySort */
        $querySort = $request->query('sort', $defaultSort);
        /** @var array $queryFilters */
        $queryFilters = $request->query('filter', []);

        $allowedSorts = $this->getAllowedSorts();
        $appliedSort = $allowedSorts->where('query', $querySort)->first();

        $appliedFilters = [];
        if (count($queryFilters) > 0) {
            $allowedFilters = $this->getAllowedFilters(SpatieQueryBuilder::for(Product::query()->with(['category', 'attributeValues.attribute'])));
            $appliedFilters = collect($queryFilters)->reduce(function (Collection $acc, array|string $values, string $query) use ($allowedFilters): Collection {
                $filter = $allowedFilters->where('query', $query)->first();
                $filterType = $filter['type'];
                $filterQuery = $filter['query'];

                if ($filterType === FrontendFilterType::INPUT->value) {
                    $acc->push($filter);
                } elseif ($filterType === FrontendFilterType::RANGE->value) {
                    $values = (array) $values;
                    [$selectedMinValue, $selectedMaxValue] = $values;
                    $filter['min_value'] = ($selectedMinValue > $filter['min_value']) ? $selectedMinValue : $filter['min_value'];
                    $filter['max_value'] = ($selectedMaxValue < $filter['max_value']) ? $selectedMaxValue : $filter['max_value'];

                    $acc->push($filter);
                } elseif ($filterType === FrontendFilterType::MULTISELECT->value) {
                    $availableValues = collect($filter['values']);
                    $correctValues = collect([]);
                    $values = (array) $values;

                    if ($filterQuery === ProductAllowedFilter::CATEGORY->value) {
                        $correctValues = $availableValues->filter(fn (string $value) => in_array($value, $values, true))->values();
                    } elseif ($filterQuery === ProductAllowedFilter::ATTRIBUTE->value) {
                        $correctValues = $availableValues
                            ->filter(function (array $attributeWithValues) use ($values): bool {
                                return array_key_exists($attributeWithValues['attribute']['slug'], $values);
                            })
                            ->map(function (array $attributeWithValues) use ($values): array {
                                $attributeWithValues['values'] = collect($values[$attributeWithValues['attribute']['slug']])
                                    ->filter(function (string $value) use ($attributeWithValues): bool {
                                        return in_array($value, $attributeWithValues['values'], true);
                                    })
                                    ->toArray();

                                return $attributeWithValues;
                            })
                            ->filter()
                            ->values();
                    }

                    if ($correctValues->count() > 0) {
                        $filter['values'] = $correctValues->toArray();

                        $acc->push($filter);
                    }
                }

                return $acc;
            }, collect([]));
        }

        return [
            'applied_filters' => $appliedFilters,
            'applied_sort' => $appliedSort,
        ];
    }

    private function getAllowedFilters(SpatieQueryBuilder $productsQuery): Collection
    {
        return collect([
            $this->buildBaseFilter(ProductAllowedFilter::TITLE),
            $this->buildBaseFilter(ProductAllowedFilter::DESCRIPTION),
            $this->buildPriceBetweenFilter($productsQuery),
            $this->buildCategoryFilter($productsQuery),
            $this->buildAttributeFilter($productsQuery),
        ]);
    }

    private function getAllowedSorts(): Collection
    {
        return collect(array_merge(
            $this->buildBaseSort(ProductAllowedSort::TITLE),
            $this->buildBaseSort(ProductAllowedSort::PRICE),
            $this->buildBaseSort(ProductAllowedSort::CREATED_AT),
        ));
    }

    private function buildBaseFilter(ProductAllowedFilter $filter): array
    {
        return [
            'query' => $filter->value,
            'title' => __(sprintf('enums.%s.%s', $filter::class, $filter->value)),
            'type' => $filter->frontendType()->value,
        ];
    }

    private function buildBaseSort(ProductAllowedSort $sort): array
    {
        return [
            [
                'query' => $sort->value,
                'title' => __(sprintf('enums.%s.%s', $sort::class, $sort->value)),
            ],
            [
                'query' => '-' . $sort->value,
                'title' => __(sprintf('enums.%s.%s', $sort::class, '-' . $sort->value)),
            ],
        ];
    }

    private function buildPriceBetweenFilter(SpatieQueryBuilder $productsQuery): array
    {
        $base = $this->buildBaseFilter(ProductAllowedFilter::PRICE_BETWEEN);

        $minPrice = $this->getMinPrice($productsQuery);
        $maxPrice = $this->getMaxPrice($productsQuery);
        $additional = [
            'min_value' => ($minPrice === null) ? null : (new Kopecks($minPrice))->roubles(),
            'max_value' => ($maxPrice === null) ? null : (new Kopecks($maxPrice))->roubles(),
        ];

        return array_merge($base, $additional);
    }

    private function buildCategoryFilter(SpatieQueryBuilder $productsQuery): array
    {
        $base = $this->buildBaseFilter(ProductAllowedFilter::CATEGORY);
        $additional = ['values' => $this->getCategories($productsQuery)];

        return array_merge($base, $additional);
    }

    private function buildAttributeFilter(SpatieQueryBuilder $productsQuery): array
    {
        $base = $this->buildBaseFilter(ProductAllowedFilter::ATTRIBUTE);
        $additional = ['values' => $this->getAttributeValues($productsQuery)];

        return array_merge($base, $additional);
    }

    private function getAttributeValues(SpatieQueryBuilder $productsQuery): array
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
            ->map(static function (ProductAttribute $attribute) use ($attributeValues): array {
                /** @var Collection $values */
                $values = $attributeValues->get($attribute->id);

                return [
                    'attribute' => ProductAttributeResource::make($attribute)->toArray(Request::capture()),
                    'values' => $values->pluck(ProductAttributeValue::getDatabaseValueColumnByAttributeType($attribute->type))->sort()->toArray(),
                ];
            })
            ->toArray();
    }

    private function getCategories(SpatieQueryBuilder $productsQuery): array
    {
        return ProductCategory::query()
            ->select(['slug', 'title'])
            ->whereHas('products', static fn (Builder $query): Builder => $query->whereIn('id', $productsQuery->getQuery()->select(['id'])))
            ->pluck('slug', 'title')
            ->toArray();
    }

    private function getMinPrice(SpatieQueryBuilder $productsQuery): ?int
    {
        return $productsQuery->min(DB::raw('COALESCE(price_discounted, price)'));
    }

    private function getMaxPrice(SpatieQueryBuilder $productsQuery): ?int
    {
        return $productsQuery->max(DB::raw('COALESCE(price_discounted, price)'));
    }
}
