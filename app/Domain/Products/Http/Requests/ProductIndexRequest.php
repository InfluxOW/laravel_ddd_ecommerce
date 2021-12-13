<?php

namespace App\Domain\Products\Http\Requests;

use App\Domain\Generic\Currency\Models\Kopecks;
use App\Domain\Generic\Query\Enums\QueryKey;
use App\Domain\Products\Enums\Query\Filter\ProductAllowedFilter;
use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            QueryKey::PAGE->value => ['nullable', 'int', 'min:1'],
            QueryKey::PER_PAGE->value => ['nullable', 'int', 'min:1', 'max:100'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::TITLE->value) => ['nullable', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::DESCRIPTION->value) => ['nullable', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CATEGORY->value) => ['nullable', 'array'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::CATEGORY->value) => ['required', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::PRICE_BETWEEN->value) => ['nullable', 'array', 'size:2'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::PRICE_BETWEEN->value) => ['nullable', 'numeric', 'min:0.01'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::ATTRIBUTE_VALUE->value) => ['nullable', 'array'],
            QueryKey::SORT->value => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        if (isset($this->filter)) {
            $this->merge([
                QueryKey::FILTER->value => array_merge(
                    $this->filter,
                    array_key_exists(ProductAllowedFilter::CATEGORY->value, $this->filter) ? [ProductAllowedFilter::CATEGORY->value => explode(',', $this->filter[ProductAllowedFilter::CATEGORY->value])] : [],
                    array_key_exists(ProductAllowedFilter::PRICE_BETWEEN->value, $this->filter) ? [ProductAllowedFilter::PRICE_BETWEEN->value => array_map(static fn (string $value): ?int => ($value === '') ? null : (int)(round($value * Kopecks::KOPECKS_IN_ROUBLE)), explode(',', $this->filter[ProductAllowedFilter::PRICE_BETWEEN->value]))] : [],
                    array_key_exists(ProductAllowedFilter::ATTRIBUTE_VALUE->value, $this->filter) ? [ProductAllowedFilter::ATTRIBUTE_VALUE->value => array_map(static fn (string $value): array => explode(',', $value), (array) $this->filter[ProductAllowedFilter::ATTRIBUTE_VALUE->value])] : [],
                ),
            ]);
        }

        if (isset($this->sort)) {
            $this->merge([
               QueryKey::SORT->value => str_starts_with($this->sort, '-') ? '-' . ltrim($this->sort, '-') : $this->sort,
            ]);
        }
    }

    public function attributes(): array
    {
        return array_combine(array_keys($this->rules()), array_keys($this->rules()));
    }
}
