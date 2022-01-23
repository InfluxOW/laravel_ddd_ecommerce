<?php

namespace App\Components\Purchasable\Http\Resources;

use Akaunting\Money\Currency;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class CurrencyResource extends JsonResource
{
    #[ArrayShape(['name' => "string", 'abbreviation' => "string", 'symbol' => "string"])]
    public function toArray($request): array
    {
        /** @var Currency $currency */
        $currency = $this->resource;

        return [
            'name' => $currency->getName(),
            'abbreviation' => $currency->getCurrency(),
            'symbol' => $currency->getSymbol(),
        ];
    }
}
