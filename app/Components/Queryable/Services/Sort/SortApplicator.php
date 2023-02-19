<?php

namespace App\Components\Queryable\Services\Sort;

use App\Components\Queryable\Abstracts\Sort\SortService;
use App\Components\Queryable\Classes\Sort\Sort;

final readonly class SortApplicator
{
    public function __construct(private SortService $service)
    {
    }

    public function applied(?string $sortQuery): ?Sort
    {
        return isset($sortQuery) ? $this->service->allowed()->filter(static fn (Sort $sort): bool => ($sort->query === $sortQuery))->first() : null;
    }
}
