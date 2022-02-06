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

    public mixed $selectedValue;

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

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'selected_value' => "mixed"])]
    public function toAppliedArray(): array
    {
        return array_merge($this->toArray(), [
            'selected_value' => $this->selectedValue,
        ]);
    }

    public function setSelectedValues(mixed ...$values): ?self
    {
        $selectedValue = Arr::first($values);

        $filter = clone($this);
        $filter->selectedValue = $this->allowedValues->filter(fn (mixed $value): bool => $value === $selectedValue)->first();

        return isset($filter->selectedValue) ? $filter : null;
    }
}
