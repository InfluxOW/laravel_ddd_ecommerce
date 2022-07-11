<?php

namespace App\Domains\Catalog\Services\Query\Filter;

use App\Components\Queryable\Abstracts\QueryService;
use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Classes\Query\Filter\ProductFilterQuery;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Http\Resources\Query\Filter\ProductFilterQueryResource;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

final class ProductFilterService implements QueryService
{
    protected SpatieQueryBuilder $productsQuery;

    protected string $currency;

    public function __construct(private ProductFilterBuilder $filterBuilder)
    {
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function setProductsQuery(SpatieQueryBuilder $productsQuery): self
    {
        $this->productsQuery = $productsQuery;

        return $this;
    }

    /**
     * @return Collection<Filter>
     */
    public function getAllowed(): Collection
    {
        return collect([
            $this->filterBuilder->buildSearchFilter(),
            $this->filterBuilder->buildCurrencyFilter($this->productsQuery),
            $this->filterBuilder->buildPriceBetweenFilter($this->productsQuery, $this->currency),
            $this->filterBuilder->buildCategoryFilter($this->productsQuery),
            $this->filterBuilder->buildAttributeValuesFilter($this->productsQuery),
        ]);
    }

    /**
     * @return AllowedFilter[]
     */
    public function getAllowedFiltersForQuery(array $validated): array
    {
        $currency = $validated[QueryKey::FILTER->value][ProductAllowedFilter::CURRENCY->name];

        return [
            /** @phpstan-ignore-next-line */
            AllowedFilter::callback(ProductAllowedFilter::SEARCH->name, static fn (Builder|Product $query, string $searchable): Builder => $query->search($searchable, orderByScore: true)),
            AllowedFilter::callback(ProductAllowedFilter::CURRENCY->name, static fn (Builder|Product $query): Builder => $query->whereHasPriceCurrency($currency)),
            AllowedFilter::callback(ProductAllowedFilter::CATEGORY->name, static fn (Builder|Product $query): Builder => $query->whereInCategory(ProductCategory::query()->visible()->hasLimitedDepth()->whereIn('slug', $validated[QueryKey::FILTER->value][ProductAllowedFilter::CATEGORY->name])->get())),
            AllowedFilter::callback(ProductAllowedFilter::PRICE_BETWEEN->name, static fn (Builder|Product $query): Builder => $query->wherePriceBetween($currency, ...$validated[QueryKey::FILTER->value][ProductAllowedFilter::PRICE_BETWEEN->name])),
            AllowedFilter::callback(ProductAllowedFilter::ATTRIBUTE_VALUE->name, static fn (Builder|Product $query): Builder => $query->whereHasAttributeValue($validated[QueryKey::FILTER->value][ProductAllowedFilter::ATTRIBUTE_VALUE->name])),
        ];
    }

    /**
     * @param Request $request
     *
     * @return Collection<Filter>
     */
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
                    $acc->push($filter->setSelectedValues(...(array) $values));

                    return $acc;
                }, collect([]))
                ->filter()
                ->values();
        }

        return $appliedFilters;
    }

    public function toResource(Request $request): JsonResource
    {
        $allowedFilters = $this->getAllowed();
        $appliedFilters = $this->getApplied($request);

        return ProductFilterQueryResource::make(new ProductFilterQuery($allowedFilters, $appliedFilters));
    }
}
