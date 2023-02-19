<?php

namespace App\Components\Queryable\Classes\Filter\Multiselect\Resources;

use App\Domains\Common\Enums\Response\ResponseValueType;
use JetBrains\PhpStorm\ArrayShape;

final readonly class NestedMultiselectFilterValuesAttribute
{
    public function __construct(public string $title, public string $query, public ResponseValueType $valuesType)
    {
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'values_type' => 'string'])]
    public function toArray(): array
    {
        return [
            'query' => $this->query,
            'title' => $this->title,
            'values_type' => $this->valuesType->name,
        ];
    }
}
