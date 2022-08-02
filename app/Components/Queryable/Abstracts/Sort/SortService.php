<?php

namespace App\Components\Queryable\Abstracts\Sort;

use App\Components\Queryable\Classes\Sort\Sort;
use App\Components\Queryable\DTOs\Sort\SortQuery;
use App\Components\Queryable\Enums\QueryKey;
use App\Components\Queryable\Http\Resources\Sort\SortQueryResource;
use App\Infrastructure\Abstracts\Http\FormRequest;
use Closure;
use Illuminate\Http\Resources\Json\JsonResource;
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

    protected function add(UnitEnum & AllowedSortEnum $sort, Closure $callback): static
    {
        $this->allowed->offsetSet($sort->name, Sort::create($sort));
        $this->callbacks->offsetSet($sort->name, AllowedSort::callback($sort->name, $callback));

        return $this;
    }

    public function applied(?string $sortQuery): ?Sort
    {
        return isset($sortQuery) ? $this->allowed()->filter(static fn (Sort $sort): bool => ($sort->query === $sortQuery))->first() : null;
    }

    public function resource(FormRequest $request): JsonResource
    {
        $allowed = $this->allowed();
        $applied = $this->applied($request->validated()[QueryKey::SORT->value] ?? null) ?? $allowed->first();

        return SortQueryResource::make(new SortQuery($allowed, $applied));
    }
}
