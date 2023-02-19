<?php

namespace App\Components\Queryable\Services\Filter;

use App\Components\Queryable\Abstracts\Filter\FilterService;
use App\Components\Queryable\Classes\Filter\Filter;
use Illuminate\Support\Collection;

final readonly class FilterApplicator
{
    public function __construct(private FilterService $service)
    {
    }

    /**
     * @return Collection<Filter>
     */
    public function applied(array $queryFilters): Collection
    {
        $appliedFilters = collect([]);
        if (count($queryFilters) > 0) {
            $allowedFilters = $this->service->allowed();

            $appliedFilters = collect($queryFilters)
                ->reduce(function (Collection $acc, array|string $values, string $filterQuery) use ($allowedFilters): Collection {
                    /** @var Filter $allowedFilter */
                    $allowedFilter = $allowedFilters->filter(static fn (Filter $filter): bool => ($filter->query === $filterQuery))->first();
                    /** @phpstan-ignore-next-line */
                    $appliedFilter = $allowedFilter->apply(...(array) $values);

                    if ($appliedFilter->isset()) {
                        $acc->push($appliedFilter);
                    }

                    return $acc;
                }, collect([]))
                ->values();
        }

        return $appliedFilters;
    }
}
