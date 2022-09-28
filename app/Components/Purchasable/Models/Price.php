<?php

namespace App\Components\Purchasable\Models;

use Akaunting\Money\Money;
use App\Components\Purchasable\Casts\MoneyCast;
use App\Components\Purchasable\Database\Builders\PriceBuilder;
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
 * @method static PriceBuilder|Price                                          newModelQuery()
 * @method static PriceBuilder|Price                                          newQuery()
 * @method static PriceBuilder|Price                                          query()
 * @method static PriceBuilder|Price                                          whereCreatedAt($value)
 * @method static PriceBuilder|Price                                          whereCurrency($value)
 * @method static PriceBuilder|Price                                          whereId($value)
 * @method static PriceBuilder|Price                                          wherePrice($value)
 * @method static PriceBuilder|Price                                          wherePriceDiscounted($value)
 * @method static PriceBuilder|Price                                          wherePurchasableId($value)
 * @method static PriceBuilder|Price                                          wherePurchasableType($value)
 * @method static PriceBuilder|Price                                          whereUpdatedAt($value)
 *
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
     * Internal
     * */

    protected static function newFactory(): PriceFactory
    {
        return PriceFactory::new();
    }

    public function newEloquentBuilder($query): PriceBuilder
    {
        /** @var PriceBuilder<self> $builder */
        $builder = new PriceBuilder($query);

        return $builder;
    }

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

    public static function getDatabasePriceExpression(): Expression
    {
        return DB::raw('COALESCE(prices.price_discounted, prices.price)');
    }
}
