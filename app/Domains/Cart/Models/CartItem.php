<?php

namespace App\Domains\Cart\Models;

use App\Domains\Catalog\Models\Product;
use App\Domains\Components\Priceable\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\Cart\Models\CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $quantity
 * @property \Akaunting\Money\Money $price_item
 * @property \Akaunting\Money\Money $price_total
 * @property \Akaunting\Money\Money $price_item_discounted
 * @property \Akaunting\Money\Money $price_total_discounted
 * @property int $product_id
 * @property string $product_title
 * @property string $product_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereProductDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereProductTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CartItem extends Model
{
    protected $casts = [
        'price_item' => MoneyCast::class,
        'price_item_discounted' => MoneyCast::class,
        'price_total' => MoneyCast::class,
        'price_total_discounted' => MoneyCast::class,
    ];

    /*
     * Relations
     * */

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
