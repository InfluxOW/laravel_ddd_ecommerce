<?php

namespace App\Domains\News\Http\Requests;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Generic\Http\Requests\IndexRequest;
use App\Domains\News\Enums\Query\Filter\ArticleAllowedFilter;
use Carbon\Carbon;

final class ArticleIndexRequest extends IndexRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            sprintf('%s.%s', QueryKey::FILTER->value, ArticleAllowedFilter::SEARCH->name) => ['nullable', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ArticleAllowedFilter::PUBLISHED_BETWEEN->name) => ['nullable', 'array', 'size:2'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ArticleAllowedFilter::PUBLISHED_BETWEEN->name) => ['nullable', 'date_format:' . config('app.date_format')],
            QueryKey::SORT->value => ['nullable', 'string'],
        ]);
    }

    public function validated($key = null, $default = null): mixed
    {
        $validated = parent::validated($key, $default);

        if ($key === null || $key === QueryKey::FILTER->value) {
            $filters = isset($key) ? $validated : ($validated[QueryKey::FILTER->value] ?? []);

            foreach ($filters[ArticleAllowedFilter::PUBLISHED_BETWEEN->name] ?? [] as $i => $date) {
                $filters[ArticleAllowedFilter::PUBLISHED_BETWEEN->name][$i] = isset($date) ? Carbon::createFromDefaultFormat($date) : null;
            }

            $validated = array_merge($validated, isset($key) ? $filters : [QueryKey::FILTER->value => $filters]);
        }

        return $validated;
    }

    public function append(): array
    {
        $appends = parent::append();

        /** @var ?Carbon $date */
        foreach ($appends[QueryKey::FILTER->value][ArticleAllowedFilter::PUBLISHED_BETWEEN->name] ?? [] as $i => $date) {
            $appends[QueryKey::FILTER->value][ArticleAllowedFilter::PUBLISHED_BETWEEN->name][$i] = isset($date) ? $date->defaultFormat() : null;
        }

        return $this->implodeFilters($appends);
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->prepareFilters();
    }

    private function prepareFilters(): void
    {
        $filter = $this->{QueryKey::FILTER->value};
        if (isset($filter)) {
            $filters = $filter;

            $this->preparePublishedBetweenFilterData($filters);

            $this->merge([
                QueryKey::FILTER->value => $filters,
            ]);
        }
    }

    private function preparePublishedBetweenFilterData(array &$filters): void
    {
        if (array_key_exists(ArticleAllowedFilter::PUBLISHED_BETWEEN->name, $filters)) {
            [$from, $to] = explode(',', $filters[ArticleAllowedFilter::PUBLISHED_BETWEEN->name]);
            $getValue = static fn (string $value): ?string => ($value === '') ? null : $value;
            $filters[ArticleAllowedFilter::PUBLISHED_BETWEEN->name] = array_map($getValue, [$from, $to]);
        }
    }
}
