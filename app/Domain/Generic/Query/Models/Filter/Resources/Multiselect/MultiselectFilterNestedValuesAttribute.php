<?php

namespace App\Domain\Generic\Query\Models\Filter\Resources\Multiselect;

use App\Domain\Generic\Enums\Response\ResponseValueType;
use JetBrains\PhpStorm\ArrayShape;

class MultiselectFilterNestedValuesAttribute
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
            'values_type' => $this->valuesType->value,
        ];
    }
}
