<?php

namespace App\Domain\Catalog\Http\Resources;

use App\Domain\Catalog\Enums\Query\Filter\ProductAllowedFilter;
use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\ProductPrice;
use App\Domain\Generic\Query\Enums\QueryKey;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class ProductResource extends JsonResource
{
    #[ArrayShape(['slug' => 'string', 'title' => "string", 'created_at' => "null|string", 'price' => 'string|optional', 'price_discounted' => "string|null|optional", 'currency' => "string", 'categories' => AnonymousResourceCollection::class, 'attributes' => AnonymousResourceCollection::class, 'description' => "string"])]
    public function toArray($request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        $currency = $request->get(QueryKey::FILTER->value)[ProductAllowedFilter::CURRENCY->value];
        /** @var ProductPrice|null $priceModel */
        $priceModel = $product->prices->where('currency', $currency)->first();

        return [
            'slug' => $product->slug,
            'title' => $product->title,
            'created_at' => $product->created_at?->format('d M Y H:i:s'),
            /* @phpstan-ignore-next-line  */
            'price' => $this->when(isset($priceModel), fn (): string => $priceModel->price->render()),
            /* @phpstan-ignore-next-line  */
            'price_discounted' => $this->when(isset($priceModel), fn (): ?string => $priceModel->price_discounted?->render()),
            'currency' => currency($currency)->getSymbol(),
            'categories' => LightProductCategoryResource::collection($product->categories->sortBy('title')),
            'attributes' => ProductAttributeValueResource::collection($product->attributeValues->sortBy('attribute.title')),
            'description' => $product->description,
        ];
    }
}
