<?php

namespace App\Domain\Catalog\Models;

use App\Domain\Catalog\Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domain\Catalog\Models\ProductAttributeValue[] $attributeValues
 * @property-read int|null $attribute_values_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domain\Catalog\Models\ProductCategory[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domain\Catalog\Models\ProductPrice[] $prices
 * @property-read int|null $prices_count
 * @method static \App\Domain\Catalog\Database\Factories\ProductFactory factory(...$parameters)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product orderByCurrentPrice(string $currency, bool $descending)
 * @method static Builder|Product query()
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereHasAttributeValue(array $attributesValuesByAttributeSlug)
 * @method static Builder|Product whereHasPriceCurrency(string $currency)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereInCategory(\Illuminate\Support\Collection $categories)
 * @method static Builder|Product wherePriceAbove(string $currency, int $minPrice)
 * @method static Builder|Product wherePriceBelow(string $currency, int $maxPrice)
 * @method static Builder|Product wherePriceBetween(string $currency, ?int $minPrice, ?int $maxPrice)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;
    use HasSlug;

    /*
     * Relations
     * */

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_categories_products', 'product_id', 'category_id')->withTimestamps();
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
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

    /*
     * Scopes
     * */

    public function scopeWhereInCategory(Builder $query, Collection $categories): void
    {
        if ($categories->isNotEmpty()) {
            $query->whereHas('categories', function (Builder $query) use ($categories): void {
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

    public function scopeWhereHasPriceCurrency(Builder $query, string $currency): void
    {
        $query->whereHas('prices', fn (Builder $query): Builder => $query->where('currency', $currency));
    }

    public function scopeWherePriceAbove(Builder $query, string $currency, int $minPrice): void
    {
        $query->whereHas('prices', fn (Builder $query): Builder => $query->where('currency', $currency)->where(ProductPrice::getDatabasePriceExpression(), '>=', $minPrice));
    }

    public function scopeWherePriceBelow(Builder $query, string $currency, int $maxPrice): void
    {
        $query->whereHas('prices', fn (Builder $query): Builder => $query->where('currency', $currency)->where(ProductPrice::getDatabasePriceExpression(), '<=', $maxPrice));
    }

    public function scopeWherePriceBetween(Builder|Product $query, string $currency, ?int $minPrice, ?int $maxPrice): void
    {
        if (isset($minPrice, $maxPrice)) {
            $query->whereHas('prices', fn (Builder $query): Builder => $query->where('currency', $currency)->whereBetween(ProductPrice::getDatabasePriceExpression(), $minPrice > $maxPrice ? [$maxPrice, $minPrice] : [$minPrice, $maxPrice]));
        } elseif (isset($minPrice)) {
            $query->wherePriceAbove($currency, $minPrice);
        } elseif (isset($maxPrice)) {
            $query->wherePriceBelow($currency, $maxPrice);
        }
    }

    public function scopeOrderByCurrentPrice(Builder $query, string $currency, bool $descending): void
    {
        $query
            ->join('product_prices', 'product_prices.product_id', '=', 'products.id')
            ->where('product_prices.currency', $currency)
            ->orderBy(ProductPrice::getDatabasePriceExpression(), $descending ? 'DESC' : 'ASC');
    }
}
