<?php

namespace App\Domain\Generic\Query\Models\Filter\Resources\Multiselect;

use App\Domain\Generic\Query\Enums\AttributeValuesType;

class MultiselectFilterNestedValuesAttribute
{
    public function __construct(public readonly string $title, public readonly string $query, public readonly AttributeValuesType $valuesType)
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
