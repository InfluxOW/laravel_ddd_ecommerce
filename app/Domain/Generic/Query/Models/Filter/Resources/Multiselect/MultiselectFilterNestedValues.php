<?php

namespace App\Domain\Generic\Query\Models\Filter\Resources\Multiselect;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class MultiselectFilterNestedValues
{
    public function __construct(public readonly MultiselectFilterNestedValuesAttribute $attribute, public readonly Collection|EloquentCollection $values)
    {
    }

    public function toArray(): array
    {
        return [
            'attribute' => $this->attribute->toArray(),
            'values' => $this->values->toArray(),
        ];
    }
}