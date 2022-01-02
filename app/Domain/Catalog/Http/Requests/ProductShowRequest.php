<?php

namespace App\Domain\Catalog\Http\Requests;

use Akaunting\Money\Currency;
use App\Domain\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Generic\Query\Enums\QueryKey;
use App\Infrastructure\Abstracts\FormRequest;
use Illuminate\Validation\Rule;

class ProductShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            sprintf('%s.%s', QueryKey::FILTER->value, ProductAllowedFilter::CURRENCY->value) => ['required', 'string', Rule::in(array_keys(Currency::getCurrencies()))],
        ];
    }
}
