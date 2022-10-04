<?php

namespace App\Components\Queryable\Abstracts\Sort;

use App\Components\Queryable\Classes\Sort\Sort;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedSort;
use UnitEnum;

abstract class SortService
{
    public function __construct()
    {
        $this->allowed = collect([]);
        $this->callbacks = collect([]);
    }

    /**
     * @var Collection<Sort>
     */
    private Collection $allowed;

    /**
     * @var Collection<AllowedSort>
     */
    private Collection $callbacks;

    private UnitEnum & IAllowedSortEnum $defaultSort;

    private UnitEnum & IAllowedSortEnum $defaultSortForSearch;

    /**
     * @return Collection<Sort>
     */
    public function allowed(): Collection
    {
        return $this->allowed->values();
    }

    /**
     * @return Collection<AllowedSort>
     */
    public function callbacks(): Collection
    {
        return $this->callbacks->values();
    }

    abstract public function build(): static;

    protected function addSort(UnitEnum & IAllowedSortEnum $sort, ?Closure $callback = null): static
    {
        if ($callback === null) {
            $callback = static function (Builder $query) use ($sort) {
                $query->reorder();

                $column = $sort->getDatabaseColumn();

                $sort->isDescending() ? $query->orderByDesc($column) : $query->orderBy($column);
            };
        }

        $this->allowed->offsetSet($sort->name, Sort::create($sort));
        $this->callbacks->offsetSet($sort->name, AllowedSort::callback($sort->name, $callback));

        return $this;
    }

    protected function addDefaultSort(UnitEnum & IAllowedSortEnum $sort, ?Closure $callback = null): static
    {
        $this->addSort($sort, $callback);

        $this->defaultSort = $sort;

        return $this;
    }

    protected function addDefaultSearchSort(UnitEnum & IAllowedSortEnum $sort, ?Closure $callback = null): static
    {
        $this->addSort($sort, $callback);

        $this->defaultSortForSearch = $sort;

        return $this;
    }

    public function getDefaultQuery(bool $isForSearch): Sort
    {
        $query = null;

        if ($isForSearch && isset($this->defaultSortForSearch)) {
            $query = $this->allowed->offsetGet($this->defaultSortForSearch->name);
        }

        if ($query === null && isset($this->defaultSort)) {
            $query = $this->allowed->offsetGet($this->defaultSort->name);
        }

        return $query ?? $this->allowed->first();
    }

    public function getDefaultCallback(bool $isForSearch): AllowedSort
    {
        $callback = null;

        if ($isForSearch) {
            $callback = isset($this->defaultSortForSearch) ? $this->callbacks->offsetGet($this->defaultSortForSearch->name) : null;
        }

        if ($callback === null && isset($this->defaultSort)) {
            $callback = $this->callbacks->offsetGet($this->defaultSort->name);
        }

        return $callback ?? $this->callbacks->first();
    }
}
