<?php

namespace App\Domain\Products\Services\Query\Filter;

use App\Domain\Generic\Query\Interfaces\QueryService;
use App\Domain\Generic\Query\Models\Filter\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

class ProductFilterService implements QueryService
{
    private SpatieQueryBuilder $productsQuery;

    public function __construct(private ProductFilterBuilder $filterBuilder)
    {
    }

    public function setProductsQuery(SpatieQueryBuilder $productsQuery): self
    {
        $this->productsQuery = $productsQuery;

        return $this;
    }

    public function getAllowed(): Collection
    {
        return collect([
            $this->filterBuilder->buildTitleFilter(),
            $this->filterBuilder->buildDescriptionFilter(),
            $this->filterBuilder->buildPriceBetweenFilter($this->productsQuery),
            $this->filterBuilder->buildCategoryFilter($this->productsQuery),
            $this->filterBuilder->buildAttributeValuesFilter($this->productsQuery),
        ]);
    }

    public function getApplied(Request $request): Collection
    {
        /** @var array $queryFilters */
        $queryFilters = $request->query('filter', []);

        $appliedFilters = collect([]);
        if (count($queryFilters) > 0) {
            $allowedFilters = $this->getAllowed();

            $appliedFilters = collect($queryFilters)
                ->reduce(function (Collection $acc, array|string $values, string $filterQuery) use ($allowedFilters): Collection {
                    /** @var Filter $filter */
                    $filter = $allowedFilters->filter(static fn (Filter $filter): bool => ($filter->query === $filterQuery))->first();
                    $acc->push($filter->ofValues(...(array) $values));

                    return $acc;
                }, collect([]))
                ->filter()
                ->values();
        }

        return $appliedFilters;
    }
}
