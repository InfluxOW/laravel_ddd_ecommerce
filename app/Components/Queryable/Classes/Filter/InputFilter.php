<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Queryable\Enums\QueryFilterType;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;

final class InputFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::INPUT;

    public mixed $selectedValue;

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string"])]
    public function toAllowedArray(): array
    {
        return $this->toArray();
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'type' => "string", 'value' => "mixed"])]
    public function toAppliedArray(): array
    {
        return array_merge($this->toArray(), [
            'selected_value' => $this->selectedValue,
        ]);
    }

    public function setSelectedValues(mixed ...$values): self
    {
        $filter = clone($this);
        $filter->selectedValue = Arr::first($values);

        return $filter;
    }
}
