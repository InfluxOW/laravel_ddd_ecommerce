<?php

namespace App\Domain\Products\Services\Filters;

use App\Domain\Products\Http\Requests\ProductIndexRequest;
use App\Domain\Products\Models\Generic\Filters\Filter;
use App\Domain\Products\Models\Product;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

class ProductFilterService
{
    public function __construct(private ProductFilterBuilder $filterBuilder)
    {
    }

    public function getAllowedFilters(SpatieQueryBuilder $productsQuery): Collection
    {
        return collect([
            $this->filterBuilder->buildTitleFilter(),
            $this->filterBuilder->buildDescriptionFilter(),
            $this->filterBuilder->buildPriceBetweenFilter($productsQuery),
            $this->filterBuilder->buildCategoryFilter($productsQuery),
            $this->filterBuilder->buildAttributeValuesFilter($productsQuery),
        ]);
    }

    public function getAppliedFilters(ProductIndexRequest $request): Collection
    {
        /** @var array $queryFilters */
        $queryFilters = $request->query('filter', []);

        $appliedFilters = collect([]);
        if (count($queryFilters) > 0) {
            $allowedFilters = $this->getAllowedFilters(SpatieQueryBuilder::for(Product::query()->with(['category', 'attributeValues.attribute'])));

            $appliedFilters = collect($queryFilters)
                ->reduce(function (Collection $acc, array|string $values, string $filterQuery) use ($allowedFilters): Collection {
                    /** @var Filter $filter */
                    $filter = $allowedFilters->filter(static fn (Filter $filter) => ($filter->query === $filterQuery))->first();
                    $acc->push($filter->ofValues(...(array) $values));

                    return $acc;
                }, collect([]));
        }

        return $appliedFilters;
    }
}
