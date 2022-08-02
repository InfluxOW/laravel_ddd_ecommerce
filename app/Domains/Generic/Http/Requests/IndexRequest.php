<?php

namespace App\Domains\Generic\Http\Requests;

use App\Components\Queryable\Enums\QueryKey;
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
}
