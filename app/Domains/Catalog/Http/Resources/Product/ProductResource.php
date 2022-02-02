<?php

namespace App\Domains\Catalog\Http\Resources\Product;

use App\Components\Mediable\Http\Resources\MediaResource;
use App\Components\Purchasable\Http\Resources\CurrencyResource;
use App\Components\Purchasable\Http\Resources\MoneyResource;
use App\Components\Queryable\Enums\QueryKey;
use App\Domains\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductPrice;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

abstract class ProductResource extends JsonResource
{
    #[ArrayShape(['media' => AnonymousResourceCollection::class, 'slug' => 'string', 'title' => "string", 'url' => "string", 'created_at' => "null|string", 'price' => 'string|optional', 'price_discounted' => "string|null|optional", 'currency' => "string", 'categories' => AnonymousResourceCollection::class, 'attributes' => AnonymousResourceCollection::class, 'description' => "string"])]
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        $currency = $request->get(QueryKey::FILTER->value)[ProductAllowedFilter::CURRENCY->value];
        /** @var ProductPrice|null $priceModel */
        $priceModel = $product->prices->where('currency', $currency)->first();

        return [
            'media' => MediaResource::collection($product->media),
            'slug' => $product->slug,
            'title' => $product->title,
            'url' => route('products.show', $product),
            'created_at' => $product->created_at?->format('d M Y H:i:s'),
            /* @phpstan-ignore-next-line */
            'price' => $this->when(isset($priceModel), fn (): MoneyResource => MoneyResource::make($priceModel->price)),
            /* @phpstan-ignore-next-line */
            'price_discounted' => $this->when(isset($priceModel, $priceModel->price_discounted), fn (): MoneyResource => MoneyResource::make($priceModel->price_discounted)),
            'currency' => CurrencyResource::make(currency($currency)),
        ];
    }
}
