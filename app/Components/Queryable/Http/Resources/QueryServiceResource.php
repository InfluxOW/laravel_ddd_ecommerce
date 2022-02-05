<?php

namespace App\Components\Queryable\Http\Resources;

use App\Components\Queryable\Enums\QueryKey;
use JetBrains\PhpStorm\ArrayShape;

final class QueryServiceResource
{
    public function __construct(protected readonly QueryKey $query, protected readonly bool $isNested, protected readonly array $applied, protected readonly array $allowed)
    {
    }

    #[ArrayShape(['query' => "string", 'is_nested' => "bool", 'applied' => "array", 'allowed' => "array"])]
    public function toArray(): array
    {
        return [
            'query' => $this->query->value,
            'is_nested' => $this->isNested,
            'applied' => $this->applied,
            'allowed' => $this->allowed,
        ];
    }
}