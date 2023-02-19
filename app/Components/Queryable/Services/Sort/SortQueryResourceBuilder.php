<?php

namespace App\Components\Queryable\Services\Sort;

use App\Components\Queryable\Abstracts\Sort\SortService;
use App\Components\Queryable\DTOs\Sort\SortQuery;
use App\Components\Queryable\Enums\QueryKey;
use App\Components\Queryable\Http\Resources\Sort\SortQueryResource;
use App\Infrastructure\Abstracts\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;

final readonly class SortQueryResourceBuilder
{
    private SortApplicator $applicator;

    public function __construct(private SortService $service)
    {
        $this->applicator = new SortApplicator($this->service);
    }

    public function resource(FormRequest $request, bool $hasAppliedSearchFilter): JsonResource
    {
        $allowed = $this->service->allowed();
        $applied = $this->applicator->applied($request->validated(QueryKey::SORT->value)) ?? $this->service->getDefaultQuery($hasAppliedSearchFilter);

        return SortQueryResource::make(new SortQuery($allowed, $applied));
    }
}
