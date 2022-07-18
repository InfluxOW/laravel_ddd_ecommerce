<?php

namespace App\Domains\Catalog\Http\Resources\Product;

use App\Components\Purchasable\Http\Resources\CurrencyResource;
use App\Components\Purchasable\Http\Resources\MoneyResource;
use App\Components\Purchasable\Models\Price;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Product;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

abstract class ProductResource extends JsonResource
{
    #[ArrayShape(['slug' => 'string', 'title' => 'string', 'url' => 'string', 'created_at' => 'string|null', 'price' => 'string|optional', 'price_discounted' => 'string|null|optional', 'currency' => 'string'])]
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        $currency = $request->get(QueryKey::FILTER->value)[ProductAllowedFilter::CURRENCY->name];
        /** @var Price|null $priceModel */
        $priceModel = $product->prices->where('currency', $currency)->first();

        return [
            'slug' => $product->slug,
            'title' => $product->title,
            'url' => route('products.show', $product),
            'created_at' => $product->created_at?->format(DateTime::RFC3339),
            /* @phpstan-ignore-next-line */
            'price' => $this->when(isset($priceModel), fn (): MoneyResource => MoneyResource::make($priceModel->price)),
            /* @phpstan-ignore-next-line */
            'price_discounted' => $this->when(isset($priceModel, $priceModel->price_discounted), fn (): MoneyResource => MoneyResource::make($priceModel->price_discounted)),
            'currency' => CurrencyResource::make(currency($currency)),
        ];
    }
}
