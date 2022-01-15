<?php

namespace App\Domains\Catalog\Http\Requests;

use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Domains\Components\Queryable\Enums\QueryKey;
use App\Infrastructure\Abstracts\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            QueryKey::PAGE->value => ['nullable', 'int', 'min:1'],
            QueryKey::PER_PAGE->value => ['nullable', 'int', 'min:1', 'max:100'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::TITLE->value) => ['nullable', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::DESCRIPTION->value) => ['nullable', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CATEGORY->value) => ['nullable', 'array'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::CATEGORY->value) => ['required', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CURRENCY->value) => ['nullable', 'string', Rule::in(app(CatalogSettings::class)->available_currencies)],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::PRICE_BETWEEN->value) => ['nullable', 'array', 'size:2'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::PRICE_BETWEEN->value) => ['nullable', 'numeric', 'min:0.01'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::ATTRIBUTE_VALUE->value) => ['nullable', 'array'],
            QueryKey::SORT->value => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $filter = $this->filter;
        if (isset($filter)) {
            $this->merge([
                QueryKey::FILTER->value => array_merge(
                    $filter,
                    array_key_exists(ProductAllowedFilter::CATEGORY->value, $filter) ? [ProductAllowedFilter::CATEGORY->value => explode(',', $filter[ProductAllowedFilter::CATEGORY->value])] : [],
                    array_key_exists(ProductAllowedFilter::PRICE_BETWEEN->value, $filter) ? [ProductAllowedFilter::PRICE_BETWEEN->value => array_map(static fn (string $value): ?int => ($value === '') ? null : (int) money($value, array_key_exists(ProductAllowedFilter::CURRENCY->value, $filter) ? $filter[ProductAllowedFilter::CURRENCY->value] : app(CatalogSettings::class)->default_currency, true)->getAmount(), explode(',', $filter[ProductAllowedFilter::PRICE_BETWEEN->value]))] : [],
                    array_key_exists(ProductAllowedFilter::ATTRIBUTE_VALUE->value, $filter) ? [ProductAllowedFilter::ATTRIBUTE_VALUE->value => array_map(static fn (string $value): array => explode(',', $value), (array) $filter[ProductAllowedFilter::ATTRIBUTE_VALUE->value])] : [],
                ),
            ]);
        }

        if (isset($this->sort)) {
            $this->merge([
                QueryKey::SORT->value => str_starts_with($this->sort, '-') ? '-' . ltrim($this->sort, '-') : $this->sort,
            ]);
        }
    }
}
