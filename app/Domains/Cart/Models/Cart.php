<?php

namespace App\Domains\Cart\Models;

use Akaunting\Money\Money;
use App\Components\Purchasable\Casts\MoneyCast;
use App\Domains\Cart\Database\Builders\CartBuilder;
use App\Domains\Common\Traits\Models\HasExtendedFunctionality;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Domains\Cart\Models\Cart
 *
 * @property int         $id
 * @property int|null    $user_id
 * @property string|null $key
 * @property string      $currency
 * @property Money       $price_items
 * @property Money       $price_items_discounted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection|CartItem[] $items
 * @property-read int|null              $items_count
 * @property-read User|null             $user
 *
 * @method static CartBuilder|Cart newModelQuery()
 * @method static CartBuilder|Cart newQuery()
 * @method static CartBuilder|Cart query()
 * @method static CartBuilder|Cart whereCreatedAt($value)
 * @method static CartBuilder|Cart whereCurrency($value)
 * @method static CartBuilder|Cart whereId($value)
 * @method static CartBuilder|Cart whereKey($value)
 * @method static CartBuilder|Cart wherePriceItems($value)
 * @method static CartBuilder|Cart wherePriceItemsDiscounted($value)
 * @method static CartBuilder|Cart whereUpdatedAt($value)
 * @method static CartBuilder|Cart whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class Cart extends Model
{
    use HasExtendedFunctionality;

    protected $fillable = ['key', 'price_items', 'price_items_discounted', 'currency'];

    protected $casts = [
        'price_items' => MoneyCast::class,
        'price_items_discounted' => MoneyCast::class,
    ];

    /*
     * Internal
     * */

    public function newEloquentBuilder($query): CartBuilder
    {
        /** @var CartBuilder<self> $builder */
        $builder = new CartBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
