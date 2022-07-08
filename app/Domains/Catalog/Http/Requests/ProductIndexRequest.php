<?php

namespace App\Domains\Catalog\Http\Requests;

use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Settings\CatalogSettings;
use App\Infrastructure\Abstracts\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ProductIndexRequest extends FormRequest
{
    private const DEFAULT_ITEMS_PER_PAGE = 20;

    public function rules(): array
    {
        return [
            QueryKey::PAGE->value => ['nullable', 'int', 'min:1'],
            QueryKey::PER_PAGE->value => ['nullable', 'int', 'min:1', 'max:100'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::SEARCH->name) => ['nullable', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CATEGORY->name) => ['nullable', 'array'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::CATEGORY->name) => ['required', 'string'],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CURRENCY->name) => ['nullable', 'string', Rule::in(app(CatalogSettings::class)->available_currencies)],
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::PRICE_BETWEEN->name) => ['nullable', 'array', 'size:2'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::PRICE_BETWEEN->name) => ['nullable', 'numeric', 'min:0.01'],
            sprintf('%s.%s.*', QueryKey::FILTER->value, ProductAllowedFilter::ATTRIBUTE_VALUE->name) => ['nullable', 'array'],
            QueryKey::SORT->value => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->prepareFilters();
        $this->preparePagination();
    }

    private function prepareFilters(): void
    {
        $filter = $this->{QueryKey::FILTER->value};
        if (isset($filter)) {
            $filters = $filter;

            $this->prepareCategoryFilterData($filters);
            $this->preparePriceBetweenFilterData($filters);
            $this->prepareAttributeValueFilterData($filters);

            $this->merge([
                QueryKey::FILTER->value => $filters,
            ]);
        }
    }

    private function prepareCategoryFilterData(array &$filters): void
    {
        if (array_key_exists(ProductAllowedFilter::CATEGORY->name, $filters)) {
            $categories = is_array($filters[ProductAllowedFilter::CATEGORY->name]) ? implode(',', $filters[ProductAllowedFilter::CATEGORY->name]) : $filters[ProductAllowedFilter::CATEGORY->name];
            $filters[ProductAllowedFilter::CATEGORY->name] = explode(',', $categories);
        }
    }

    private function preparePriceBetweenFilterData(array &$filters): void
    {
        if (array_key_exists(ProductAllowedFilter::PRICE_BETWEEN->name, $filters)) {
            [$from, $to] = explode(',', $filters[ProductAllowedFilter::PRICE_BETWEEN->name]);
            $getPriceAmount = static fn (string $value): ?int => ($value === '') ? null : (int) money($value, $filters[ProductAllowedFilter::CURRENCY->name], true)->getAmount();
            $filters[ProductAllowedFilter::PRICE_BETWEEN->name] = array_map($getPriceAmount, [$from, $to]);
        }
    }

    private function prepareAttributeValueFilterData(array &$filters): void
    {
        if (array_key_exists(ProductAllowedFilter::ATTRIBUTE_VALUE->name, $filters)) {
            $filters[ProductAllowedFilter::ATTRIBUTE_VALUE->name] = array_map(static fn (string $value): array => explode(',', $value), (array) $filters[ProductAllowedFilter::ATTRIBUTE_VALUE->name]);
        }
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
}
