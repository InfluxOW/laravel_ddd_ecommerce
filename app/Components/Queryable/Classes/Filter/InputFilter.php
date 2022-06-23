<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Queryable\Enums\QueryFilterType;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;

final class InputFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::INPUT;

    public string|int|bool|float|null $selectedValue;

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string'])]
    public function toAllowedArray(): array
    {
        return $this->toArray();
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'value' => 'string|int|bool|float|null'])]
    public function toAppliedArray(): array
    {
        return array_merge($this->toArray(), [
            'selected_value' => $this->selectedValue,
        ]);
    }

    public function setSelectedValues(string|int|bool|float|array|null ...$values): self
    {
        $filter = clone $this;
        $filter->selectedValue = Arr::first($values, static fn (string|int|bool|float|array|null $value) => ! is_array($value));

        return $filter;
    }
}
