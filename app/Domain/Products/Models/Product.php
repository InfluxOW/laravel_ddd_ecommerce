<?php

namespace App\Domain\Products\Models;

use App\Domain\Generic\Currency\Casts\KopecksCast;
use App\Domain\Products\Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property \App\Domain\Generic\Currency\Models\Kopecks $price
 * @property \App\Domain\Generic\Currency\Models\Kopecks|null $price_discounted
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domain\Products\Models\ProductAttributeValue[] $attributeValues
 * @property-read int|null $attribute_values_count
 * @property-read \App\Domain\Products\Models\ProductCategory $category
 * @method static \App\Domain\Products\Database\Factories\ProductFactory factory(...$parameters)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product orderByCurrentPrice(bool $descending)
 * @method static Builder|Product query()
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereHasAttributeValue(array $attributesValuesByAttributeSlug)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereInCategory(array $categoriesSlugs)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product wherePriceAbove(int $minPrice)
 * @method static Builder|Product wherePriceBelow(int $maxPrice)
 * @method static Builder|Product wherePriceBetween(?int $minPrice, ?int $maxPrice)
 * @method static Builder|Product wherePriceDiscounted($value)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $casts = [
        'price' => KopecksCast::class,
        'price_discounted' => KopecksCast::class,
    ];

    /*
     * Relations
     * */

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /*
     * Attributes
     * */

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /*
     * Helpers
     * */

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    private function getDatabasePriceExpression(): Expression
    {
        return DB::raw('COALESCE(price_discounted, price)');
    }

    /*
     * Scopes
     * */

    public function scopeWhereInCategory(Builder $query, array $categoriesSlugs): void
    {
        $categories = ProductCategory::query()->whereIn('slug', $categoriesSlugs)->get();

        if ($categories->isNotEmpty()) {
            $query->whereHas('category', function (Builder $query) use ($categories): void {
                foreach ($categories as $i => $category) {
                    $operation = ($i === 0) ? 'where' : 'orWhere';

                    $query->$operation(function (Builder $query) use ($category): void {
                        $query
                            ->where('left', '>=', $category->left)
                            ->where('right', '<=', $category->right);
                    });
                }
            });
        }
    }

    public function scopeWhereHasAttributeValue(Builder $query, array $attributesValuesByAttributeSlug): void
    {
        $attributes = ProductAttribute::query()->whereIn('slug', array_keys($attributesValuesByAttributeSlug))->get();

        if ($attributes->isNotEmpty()) {
            $query->whereHas('attributeValues', function (Builder $query) use ($attributes, $attributesValuesByAttributeSlug): void {
                foreach ($attributes as $i => $attribute) {
                    $values = (array) $attributesValuesByAttributeSlug[$attribute->slug];
                    $operation = ($i === 0) ? 'where' : 'orWhere';

                    $query->$operation(static fn (Builder $query): Builder => $query->whereIn(ProductAttributeValue::getDatabaseValueColumnByAttributeType($attribute->values_type), $values));
                }
            });
        }
    }

    public function scopeWherePriceAbove(Builder $query, int $minPrice): void
    {
        $query->where($this->getDatabasePriceExpression(), '>=', $minPrice);
    }

    public function scopeWherePriceBelow(Builder $query, int $maxPrice): void
    {
        $query->where($this->getDatabasePriceExpression(), '<=', $maxPrice);
    }

    public function scopeWherePriceBetween(Builder|Product $query, ?int $minPrice, ?int $maxPrice): void
    {
        if (isset($minPrice, $maxPrice)) {
            $query->whereBetween($this->getDatabasePriceExpression(), [$minPrice, $maxPrice]);
        } elseif (isset($minPrice)) {
            $query->wherePriceAbove($minPrice);
        } elseif (isset($maxPrice)) {
            $query->wherePriceBelow($maxPrice);
        }
    }

    public function scopeOrderByCurrentPrice(Builder $query, bool $descending): void
    {
        $query->orderBy($this->getDatabasePriceExpression(), $descending ? 'DESC' : 'ASC');
    }
}
