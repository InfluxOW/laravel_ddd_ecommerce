<?php

namespace App\Domains\News\Http\Requests;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Generic\Http\Requests\IndexRequest;
use App\Domains\News\Enums\Query\Filter\ArticleAllowedFilter;
use Carbon\Carbon;
use DateTime;

final class ArticleIndexRequest extends IndexRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            sprintf('%s.%s', QueryKey::FILTER->value, ArticleAllowedFilter::SEARCH->name) => ['nullable', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ArticleAllowedFilter::PUBLISHED_AT->name) => ['nullable', 'array', 'size:2'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ArticleAllowedFilter::PUBLISHED_AT->name) => ['nullable', 'date_format:' . DateTime::RFC3339],
            QueryKey::SORT->value => ['nullable', 'string'],
        ]);
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        foreach ($validated[QueryKey::FILTER->value][ArticleAllowedFilter::PUBLISHED_AT->name] ?? [] as $i => $date) {
            $validated[QueryKey::FILTER->value][ArticleAllowedFilter::PUBLISHED_AT->name][$i] = isset($date) ? Carbon::createFromFormat(DateTime::RFC3339, $date) : null;
        }

        return $validated;
    }

    public function append(): array
    {
        $appends = parent::append();

        /** @var ?Carbon $date */
        foreach ($appends[QueryKey::FILTER->value][ArticleAllowedFilter::PUBLISHED_AT->name] ?? [] as $i => $date) {
            $appends[QueryKey::FILTER->value][ArticleAllowedFilter::PUBLISHED_AT->name][$i] = isset($date) ? $date->format(DateTime::RFC3339) : null;
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

            $this->preparePublishedAtFilterData($filters);

            $this->merge([
                QueryKey::FILTER->value => $filters,
            ]);
        }
    }

    private function preparePublishedAtFilterData(array &$filters): void
    {
        if (array_key_exists(ArticleAllowedFilter::PUBLISHED_AT->name, $filters)) {
            [$from, $to] = explode(',', $filters[ArticleAllowedFilter::PUBLISHED_AT->name]);
            $getValue = static fn (string $value): ?string => ($value === '') ? null : $value;
            $filters[ArticleAllowedFilter::PUBLISHED_AT->name] = array_map($getValue, [$from, $to]);
        }
    }
}
