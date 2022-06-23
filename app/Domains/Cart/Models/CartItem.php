<?php

namespace App\Domains\Cart\Models;

use App\Components\Purchasable\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\Cart\Models\CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $quantity
 * @property \Akaunting\Money\Money $price_item
 * @property \Akaunting\Money\Money $price_item_discounted
 * @property \Akaunting\Money\Money $price_total
 * @property \Akaunting\Money\Money $price_total_discounted
 * @property string $purchasable_type
 * @property int $purchasable_id
 * @property array $purchasable_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Domains\Cart\Models\Cart $cart
 * @property-read string $currency
 * @property-read Model|\Eloquent $purchasable
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePriceItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePriceItemDiscounted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePriceTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePriceTotalDiscounted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePurchasableData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePurchasableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePurchasableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class CartItem extends Model
{
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
     * Attributes
     * */

    public function getCurrencyAttribute(): string
    {
        return $this->cart->currency;
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
}
