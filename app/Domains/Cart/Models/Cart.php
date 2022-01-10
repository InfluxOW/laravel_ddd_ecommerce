<?php

namespace App\Domains\Cart\Models;

use App\Domains\Components\Priceable\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Domains\Cart\Models\Cart
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $key
 * @property \Akaunting\Money\Money $price_items
 * @property \Akaunting\Money\Money $price_items_discounted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Cart\Models\CartItem[] $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePriceItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart wherePriceItemsDiscounted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    protected $fillable = ['key'];
    protected $casts = [
        'price_items' => MoneyCast::class,
        'price_items_discounted' => MoneyCast::class,
    ];

    /*
     * Relations
     * */

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
