<?php

namespace App\Components\Queryable\Classes\Filter\Resources\Multiselect;

use App\Components\Generic\Enums\Response\ResponseValueType;

final class MultiselectFilterNestedValuesAttribute
{
    public function __construct(public readonly string $title, public readonly string $query, public readonly ResponseValueType $valuesType)
    {
    }

    public function toArray(): array
    {
        return [
            'query' => $this->query,
            'title' => $this->title,
            'values_type' => $this->valuesType->value,
        ];
    }
}
