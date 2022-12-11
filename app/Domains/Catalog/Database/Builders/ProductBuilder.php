<?php

namespace App\Domains\Catalog\Database\Builders;

use Akaunting\Money\Money;
use App\Components\Attributable\Database\Builders\AttributeValueBuilder;
use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Models\Attribute;
use App\Components\Attributable\Models\AttributeValue;
use App\Components\Purchasable\Database\Builders\PriceBuilder;
use App\Components\Purchasable\Exceptions\IncompatibleCurrenciesException;
use App\Components\Purchasable\Models\Price;
use App\Domains\Catalog\Models\Product;
use App\Domains\Common\Enums\BooleanString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @mixin Product
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class ProductBuilder extends Builder
{
    public function whereInCategory(Collection $categories): self
    {
        if ($categories->isNotEmpty()) {
            $this->whereHas('categories', function (ProductCategoryBuilder $query) use ($categories): void {
                foreach ($categories as $i => $category) {
                    $operation = $i === 0 ? 'where' : 'orWhere';

                    $query->$operation(function (ProductCategoryBuilder $query) use ($category): void {
                        $query
                            ->where('left', '>=', $category->left)
                            ->where('right', '<=', $category->right);
                    });
                }
            });
        }

        return $this;
    }

    public function whereHasAttributeValue(array $attributesValuesByAttributeSlug): self
    {
        $attributes = Attribute::query()->whereIn('slug', array_keys($attributesValuesByAttributeSlug))->get();

        if ($attributes->isNotEmpty()) {
            foreach ($attributes as $attribute) {
                $values = (array) $attributesValuesByAttributeSlug[$attribute->slug];
                if ($attribute->values_type === AttributeValuesType::BOOLEAN) {
                    $values = array_map(static fn (string|int|bool|float $value): bool => Str::of((string) $value)->lower()->toString() === BooleanString::_TRUE->value, $values);
                }

                $this->whereHas('attributeValues', function (AttributeValueBuilder $query) use ($attribute, $values): void {
                    $query->where('attribute_id', $attribute->id)->whereIn(AttributeValue::getDatabaseValueColumnByAttributeType($attribute->values_type), $values);
                });
            }
        }

        return $this;
    }

    public function whereHasPriceCurrency(string $currency): self
    {
        $this->whereHas('prices', fn (PriceBuilder $query) => $query->where('currency', $currency));

        return $this;
    }

    public function wherePriceAbove(Money $price): self
    {
        $this->whereHas('prices', fn (PriceBuilder $query) => $query->where('currency', $price->getCurrency()->getCurrency())->where(Price::getDatabasePriceExpression(), '>=', $price->getAmount()));

        return $this;
    }

    public function wherePriceBelow(Money $price): self
    {
        $this->whereHas('prices', fn (PriceBuilder $query) => $query->where('currency', $price->getCurrency()->getCurrency())->where(Price::getDatabasePriceExpression(), '<=', $price->getAmount()));

        return $this;
    }

    public function wherePriceBetween(?Money $min, ?Money $max): self
    {
        if (isset($min) && isset($max)) {
            throw_unless($min->getCurrency()->getCurrency() === $max->getCurrency()->getCurrency(), IncompatibleCurrenciesException::class);

            $this->whereHas('prices', fn (PriceBuilder $query) => $query->where('currency', $min->getCurrency()->getCurrency())->whereBetween(Price::getDatabasePriceExpression(), $min > $max ? [$max->getAmount(), $min->getAmount()] : [$min->getAmount(), $max->getAmount()]));
        } elseif (isset($min)) {
            $this->wherePriceAbove($min);
        } elseif (isset($max)) {
            $this->wherePriceBelow($max);
        }

        return $this;
    }

    public function orderByCurrentPrice(string $currency, bool $descending): self
    {
        $this
            ->join('prices', 'prices.purchasable_id', '=', 'products.id')
            ->where('prices.purchasable_type', Relation::getAlias(Product::class))
            ->where('prices.currency', $currency)
            ->orderBy(Price::getDatabasePriceExpression(), $descending ? 'DESC' : 'ASC');

        return $this;
    }

    public function displayable(): self
    {
        $this->where('products.is_displayable', true);

        return $this;
    }
}
