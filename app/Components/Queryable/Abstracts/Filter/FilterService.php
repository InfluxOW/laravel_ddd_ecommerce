<?php

namespace App\Components\Queryable\Abstracts\Filter;

use App\Components\Queryable\Abstracts\FilterBuilder;
use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\Enums\QueryKey;
use Closure;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use UnitEnum;

abstract class FilterService
{
    protected array $validated;

    /**
     * @var Collection<Filter>
     */
    private Collection $allowed;

    /**
     * @var Collection<AllowedFilter>
     */
    private Collection $callbacks;

    public function __construct(protected readonly FilterBuilder $builder)
    {
        $this->allowed = collect([]);
        $this->callbacks = collect([]);
    }

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

    protected function add(UnitEnum & IAllowedFilterEnum $filter, Closure $callback): static
    {
        $this->allowed->offsetSet($filter->name, $this->builder->build($filter));
        $this->callbacks->offsetSet($filter->name, AllowedFilter::callback($filter->name, $callback));

        return $this;
    }

    protected function getFilter(UnitEnum & IAllowedFilterEnum $filter): mixed
    {
        return $this->validated[QueryKey::FILTER->value][$filter->name];
    }
}
