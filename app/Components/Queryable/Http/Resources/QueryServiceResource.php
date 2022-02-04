<?php

namespace App\Components\Queryable\Http\Resources;

use App\Components\Queryable\Enums\QueryKey;

final class QueryServiceResource
{
    public function __construct(protected readonly QueryKey $query, protected readonly bool $isNested, protected readonly array $applied, protected readonly array $allowed)
    {
    }

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
