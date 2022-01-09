<?php

namespace App\Domains\Components\Priceable\Http\Resources;

use Akaunting\Money\Money;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class MoneyResource extends JsonResource
{
    #[ArrayShape(['value' => "float", 'amount' => "float|int", 'render' => "string"])]
    public function toArray($request): array
    {
        /** @var Money $money */
        $money = $this->resource;

        return [
            'value' => $money->getValue(),
            'amount' => $money->getAmount(),
            'render' => $money->render(),
        ];
    }
}
