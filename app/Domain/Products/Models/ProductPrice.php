<?php

namespace App\Domain\Products\Models;

use App\Domain\Generic\Currency\Casts\MoneyCast;
use App\Domain\Products\Database\Factories\ProductPriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

/**
 * App\Domain\Products\Models\ProductPrice
 *
 * @property int $id
 * @property int $product_id
 * @property \Akaunting\Money\Money $price
 * @property \Akaunting\Money\Money|null $price_discounted
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Domain\Products\Models\Product $product
 * @method static \App\Domain\Products\Database\Factories\ProductPriceFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice wherePriceDiscounted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductPrice extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => MoneyCast::class,
        'price_discounted' => MoneyCast::class,
    ];

    /*
     * Relations
     * */

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /*
     * Helpers
     * */

    protected static function newFactory(): ProductPriceFactory
    {
        return ProductPriceFactory::new();
    }

    public static function getDatabasePriceExpression(): Expression
    {
        return DB::raw('COALESCE(price_discounted, price)');
    }
}
