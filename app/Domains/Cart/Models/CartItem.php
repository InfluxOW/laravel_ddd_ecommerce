<?php

namespace App\Domains\Cart\Models;

use App\Components\Purchasable\Casts\MoneyCast;
use App\Domains\Cart\Database\Builders\CartItemBuilder;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\Cart\Models\CartItem
 *
 * @property int                             $id
 * @property int                             $cart_id
 * @property int                             $quantity
 * @property \Akaunting\Money\Money          $price_item
 * @property \Akaunting\Money\Money          $price_item_discounted
 * @property \Akaunting\Money\Money          $price_total
 * @property \Akaunting\Money\Money          $price_total_discounted
 * @property string                          $purchasable_type
 * @property int                             $purchasable_id
 * @property array                           $purchasable_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Domains\Cart\Models\Cart $cart
 * @property-read string                        $currency
 * @property-read Model|\Eloquent               $purchasable
 *
 * @method static CartItemBuilder|CartItem newModelQuery()
 * @method static CartItemBuilder|CartItem newQuery()
 * @method static CartItemBuilder|CartItem query()
 * @method static CartItemBuilder|CartItem whereCartId($value)
 * @method static CartItemBuilder|CartItem whereCreatedAt($value)
 * @method static CartItemBuilder|CartItem whereId($value)
 * @method static CartItemBuilder|CartItem wherePriceItem($value)
 * @method static CartItemBuilder|CartItem wherePriceItemDiscounted($value)
 * @method static CartItemBuilder|CartItem wherePriceTotal($value)
 * @method static CartItemBuilder|CartItem wherePriceTotalDiscounted($value)
 * @method static CartItemBuilder|CartItem wherePurchasableData($value)
 * @method static CartItemBuilder|CartItem wherePurchasableId($value)
 * @method static CartItemBuilder|CartItem wherePurchasableType($value)
 * @method static CartItemBuilder|CartItem whereQuantity($value)
 * @method static CartItemBuilder|CartItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class CartItem extends Model
{
    use HasExtendedFunctionality;

    public const MAX_QUANTITY = 99;

    protected $casts = [
        'price_item' => MoneyCast::class,
        'price_item_discounted' => MoneyCast::class,
        'price_total' => MoneyCast::class,
        'price_total_discounted' => MoneyCast::class,
        'purchasable_data' => 'array',
    ];

    protected $fillable = [
        'quantity',
        'price_item',
        'price_item_discounted',
        'price_total',
        'price_total_discounted',
        'purchasable_data',
    ];

    /*
     * Internal
     * */

    public function newEloquentBuilder($query): CartItemBuilder
    {
        /** @var CartItemBuilder<self> $builder */
        $builder = new CartItemBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function purchasable(): BelongsTo
    {
        return $this->morphTo();
    }

    /*
     * Attributes
     * */

    public function getCurrencyAttribute(): string
    {
        return $this->cart->currency;
    }
}
