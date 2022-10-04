<?php

namespace App\Components\Queryable\Services\Filter;

use App\Components\Queryable\Abstracts\Filter\FilterService;
use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\DTOs\Filter\FilterQuery;
use App\Components\Queryable\Enums\QueryKey;
use App\Components\Queryable\Http\Resources\Filter\FilterQueryResource;
use App\Infrastructure\Abstracts\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;

final class FilterQueryResourceBuilder
{
    private readonly FilterApplicator $applicator;

    public function __construct(private readonly FilterService $service)
    {
        $this->applicator = new FilterApplicator($this->service);
    }

    public function resource(FormRequest $request): JsonResource
    {
        return FilterQueryResource::make(
            new FilterQuery(
                $this->service->allowed(),
                $this->applicator->applied($request->validated(QueryKey::FILTER->value, []))
            )
        );
    }

    public function hasAppliedSearchFilter(FormRequest $request): bool
    {
        $searchFilter = $this->service->getSearchFilter();

        return isset($searchFilter) && $this->applicator->applied($request->validated(QueryKey::FILTER->value, []))->filter(fn (Filter $filter): bool => $filter->query === $searchFilter->query)->isNotEmpty();
    }
}
