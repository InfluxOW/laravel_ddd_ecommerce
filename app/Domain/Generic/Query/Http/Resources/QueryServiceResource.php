<?php

namespace App\Domain\Generic\Query\Http\Resources;

use App\Domain\Generic\Query\Enums\QueryKey;

class QueryServiceResource
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
