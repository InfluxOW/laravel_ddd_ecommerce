<?php

namespace App\Components\Queryable\Abstracts\Filter;

use App\Components\Queryable\Abstracts\FilterBuilder;
use App\Components\Queryable\Classes\Filter\Filter;
use Closure;
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
}
