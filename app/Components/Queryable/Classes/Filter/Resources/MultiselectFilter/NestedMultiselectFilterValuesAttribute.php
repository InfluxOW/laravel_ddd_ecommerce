<?php

namespace App\Components\Queryable\Classes\Filter\Resources\MultiselectFilter;

use App\Domains\Generic\Enums\Response\ResponseValueType;
use JetBrains\PhpStorm\ArrayShape;

final class NestedMultiselectFilterValuesAttribute
{
    public function __construct(public readonly string $title, public readonly string $query, public readonly ResponseValueType $valuesType)
    {
    }

    #[ArrayShape(['query' => "string", 'title' => "string", 'values_type' => "string"])]
    public function toArray(): array
    {
        return [
            'query' => $this->query,
            'title' => $this->title,
            'values_type' => $this->valuesType->name,
        ];
    }
}
