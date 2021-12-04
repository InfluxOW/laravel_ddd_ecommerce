<?php

namespace App\Domain\Products\Models\Generic\Filters\Resources\Multiselect;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class MultiselectFilterNestedValues
{
    public function __construct(public MultiselectFilterNestedValuesAttribute $attribute, public Collection|EloquentCollection $values)
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
