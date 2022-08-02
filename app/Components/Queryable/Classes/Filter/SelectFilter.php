<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Queryable\Enums\QueryFilterType;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use UnitEnum;

final class SelectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::SELECT;

    private string|int|bool|float|null $selected;

    public function __construct(
        UnitEnum $filter,
        public Collection|EloquentCollection $allowed
    ) {
        parent::__construct($filter);
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'values' => 'array'])]
    public function allowed(): array
    {
        return array_merge($this->toArray(), [
            'allowed' => $this->allowed->toArray(),
        ]);
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'selected_value' => 'string|int|bool|float|null'])]
    public function applied(): array
    {
        return array_merge($this->toArray(), [
            'selected' => $this->selected,
        ]);
    }

    public function apply(string|int|bool|float $value): self
    {
        $filter = clone $this;

        $filter->selected = $filter->allowed->filter(fn (string|int|bool|float $allowed): bool => $allowed === $value)->first();

        return $filter;
    }

    public function isset(): bool
    {
        return isset($this->selected);
    }
}
