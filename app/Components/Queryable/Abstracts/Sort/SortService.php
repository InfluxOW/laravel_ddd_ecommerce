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

    /**
     * @return Collection<Sort>
     */
    public function allowed(): Collection
    {
        return $this->allowed;
    }

    /**
     * @return Collection<AllowedSort>
     */
    public function callbacks(): Collection
    {
        return $this->callbacks;
    }

    abstract public function build(): static;

    protected function add(UnitEnum & IAllowedSortEnum $sort, ?Closure $callback = null): static
    {
        if ($callback === null) {
            $callback = static function (Builder $query) use ($sort): Builder {
                $query->reorder();

                $column = $sort->getDatabaseColumn();

                return $sort->isDescending() ? $query->orderByDesc($column) : $query->orderBy($column);
            };
        }

        $this->allowed->offsetSet($sort->name, Sort::create($sort));
        $this->callbacks->offsetSet($sort->name, AllowedSort::callback($sort->name, $callback));

        return $this;
    }
}
