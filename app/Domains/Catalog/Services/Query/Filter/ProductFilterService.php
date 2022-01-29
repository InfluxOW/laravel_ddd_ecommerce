<?php

namespace App\Domains\Catalog\Services\Query\Filter;

use App\Components\Queryable\Abstracts\QueryService;
use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\Enums\QueryKey;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

final class ProductFilterService implements QueryService
{
    protected SpatieQueryBuilder $productsQuery;
    protected string $currency;

    public function __construct(private ProductFilterBuilder $filterBuilder)
    {
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function setProductsQuery(SpatieQueryBuilder $productsQuery): static
    {
        $this->productsQuery = $productsQuery;

        return $this;
    }

    public function getAllowed(): Collection
    {
        return collect([
            $this->filterBuilder->buildTitleFilter(),
            $this->filterBuilder->buildDescriptionFilter(),
            $this->filterBuilder->buildCurrencyFilter($this->productsQuery),
            $this->filterBuilder->buildPriceBetweenFilter($this->productsQuery, $this->currency),
            $this->filterBuilder->buildCategoryFilter($this->productsQuery),
            $this->filterBuilder->buildAttributeValuesFilter($this->productsQuery),
        ]);
    }

    public function getApplied(Request $request): Collection
    {
        /** @var array $queryFilters */
        $queryFilters = $request->query(QueryKey::FILTER->value, []);

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
