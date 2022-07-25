<?php

namespace App\Components\Purchasable\Models;

use Akaunting\Money\Money;
use App\Components\Purchasable\Casts\MoneyCast;
use App\Components\Purchasable\Database\Factories\PriceFactory;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

/**
 * App\Components\Purchasable\Models\Price
 *
 * @property int                             $id
 * @property string                          $purchasable_type
 * @property int                             $purchasable_id
 * @property Money                           $price
 * @property Money|null                      $price_discounted
 * @property string                          $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $purchasable
 *
 * @method static \App\Components\Purchasable\Database\Factories\PriceFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Price newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Price query()
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price wherePriceDiscounted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price wherePurchasableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price wherePurchasableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Price whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class Price extends Model
{
    use HasExtendedFunctionality;
    use HasFactory;

    protected $casts = [
        'price' => MoneyCast::class,
        'price_discounted' => MoneyCast::class,
    ];

    protected $fillable = [
        'currency',
        'price',
        'price_discounted',
    ];

    /*
     * Relations
     * */

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    /*
     * Helpers
     * */

    protected static function newFactory(): PriceFactory
    {
        return PriceFactory::new();
    }

    public static function getDatabasePriceExpression(): Expression
    {
        return DB::raw('COALESCE(prices.price_discounted, prices.price)');
    }
}
