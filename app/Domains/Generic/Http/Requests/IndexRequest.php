<?php

namespace App\Domains\Generic\Http\Requests;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Generic\Utils\StringUtils;
use App\Infrastructure\Abstracts\Http\FormRequest;

abstract class IndexRequest extends FormRequest
{
    protected const DEFAULT_ITEMS_PER_PAGE = 20;

    public function rules(): array
    {
        return [
            QueryKey::PAGE->value => ['nullable', 'int', 'min:1'],
            QueryKey::PER_PAGE->value => ['nullable', 'int', 'min:1', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->preparePagination();
    }

    private function preparePagination(): void
    {
        $perPage = $this->{QueryKey::PER_PAGE->value};
        if ($perPage === null) {
            $this->merge([
                QueryKey::PER_PAGE->value => self::DEFAULT_ITEMS_PER_PAGE,
            ]);
        }

        $page = $this->{QueryKey::PAGE->value};
        if ($page === null) {
            $this->merge([
                QueryKey::PAGE->value => 1,
            ]);
        }
    }

    public function append(): array
    {
        return $this->validated();
    }

    protected function implodeFilters(array $query): array
    {
        $toString = static fn (string|int|float|bool|null $value): string => is_bool($value) ? StringUtils::boolToString($value) : (string) $value;
        $implode = static function (array $values) use ($toString, &$implode): array|string {
            $result = [];
            foreach ($values as $key => $value) {
                $result[$key] = is_array($value) ? $implode($value) : $toString($value);
            }

            /** @phpstan-ignore-next-line */
            return array_is_list($result) ? implode(',', $result) : $result;
        };

        foreach ($query[QueryKey::FILTER->value] ?? [] as $filter => $values) {
            if (is_array($values)) {
                $query[QueryKey::FILTER->value][$filter] = $implode($values);
            }
        }

        return $query;
    }
}
