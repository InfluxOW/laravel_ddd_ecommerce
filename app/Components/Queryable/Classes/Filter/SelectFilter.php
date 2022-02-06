<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Queryable\Enums\QueryFilterType;
use BackedEnum;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

final class SelectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::SELECT;

    public string|int|bool|float|null $selectedValue;

    public function __construct(
        BackedEnum $filter,
        public Collection|EloquentCollection $allowedValues
    ) {
        parent::__construct($filter);
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'values' => "array"])]
    public function toAllowedArray(): array
    {
        return array_merge($this->toArray(), [
            'allowed_values' => $this->allowedValues->toArray(),
        ]);
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'selected_value' => "string|int|bool|float|null"])]
    public function toAppliedArray(): array
    {
        return array_merge($this->toArray(), [
            'selected_value' => $this->selectedValue,
        ]);
    }

    public function setSelectedValues(string|int|bool|float|array|null ...$values): ?self
    {
        $selectedValue = Arr::first($values, static fn (string|int|bool|float|array|null $value) => ! is_array($value));

        $filter = clone($this);
        $filter->selectedValue = $this->allowedValues->filter(fn (string|int|bool|float|array|null $value): bool => $value === $selectedValue)->first();

        return isset($filter->selectedValue) ? $filter : null;
    }
}
