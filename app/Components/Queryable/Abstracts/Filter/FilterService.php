<?php

namespace App\Components\Queryable\Abstracts\Filter;

use App\Components\Queryable\Abstracts\FilterBuilder;
use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\DTOs\Filter\FilterQuery;
use App\Components\Queryable\Enums\QueryKey;
use App\Components\Queryable\Http\Resources\Filter\FilterQueryResource;
use App\Infrastructure\Abstracts\Http\FormRequest;
use Closure;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use UnitEnum;

abstract class FilterService
{
    public function __construct(protected readonly FilterBuilder $builder)
    {
        $this->allowed = collect([]);
        $this->callbacks = collect([]);
    }

    /**
     * @var Collection<Filter>
     */
    private Collection $allowed;

    /**
     * @var Collection<AllowedFilter>
     */
    private Collection $callbacks;

    /**
     * @return Collection<Filter>
     */
    public function allowed(): Collection
    {
        return $this->allowed;
    }

    /**
     * @return Collection<AllowedFilter>
     */
    public function callbacks(): Collection
    {
        return $this->callbacks;
    }

    abstract public function build(): static;

    protected function add(UnitEnum & AllowedFilterEnum $filter, Closure $callback): static
    {
        $this->allowed->offsetSet($filter->name, $this->builder->build($filter));
        $this->callbacks->offsetSet($filter->name, AllowedFilter::callback($filter->name, $callback));

        return $this;
    }

    /**
     * @return Collection<Filter>
     */
    protected function applied(array $queryFilters): Collection
    {
        $appliedFilters = collect([]);
        if (count($queryFilters) > 0) {
            $allowedFilters = $this->allowed();

            $appliedFilters = collect($queryFilters)
                ->reduce(function (Collection $acc, array|string $values, string $filterQuery) use ($allowedFilters): Collection {
                    /** @var Filter $allowedFilter */
                    $allowedFilter = $allowedFilters->filter(static fn (Filter $filter): bool => ($filter->query === $filterQuery))->first();
                    /** @phpstan-ignore-next-line */
                    $appliedFilter = $allowedFilter->apply(...(array) $values);

                    if ($appliedFilter->isset()) {
                        $acc->push($appliedFilter);
                    }

                    return $acc;
                }, collect([]))
                ->values();
        }

        return $appliedFilters;
    }

    public function resource(FormRequest $request): JsonResource
    {
        return FilterQueryResource::make(new FilterQuery($this->allowed(), $this->applied($request->validated()[QueryKey::FILTER->value] ?? [])));
    }
}
