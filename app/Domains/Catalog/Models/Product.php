<?php

namespace App\Domains\Catalog\Models;

use Akaunting\Money\Money;
use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Models\Attribute;
use App\Components\Attributable\Models\AttributeValue;
use App\Components\Purchasable\Abstracts\Purchasable;
use App\Components\Purchasable\Exceptions\IncompatibleCurrenciesException;
use App\Components\Purchasable\Models\Price;
use App\Domains\Catalog\Database\Factories\ProductFactory;
use App\Domains\Catalog\Enums\Media\ProductMediaCollectionKey;
use App\Domains\Catalog\Jobs\Export\ProductsExportJob;
use App\Domains\Catalog\Models\Pivot\ProductProductCategory;
use App\Domains\Generic\Enums\BooleanString;
use App\Domains\Generic\Interfaces\Exportable;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use App\Domains\Generic\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JeroenG\Explorer\Application\Explored;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Domains\Catalog\Models\Product
 *
 * @property int                             $id
 * @property string                          $title
 * @property string                          $slug
 * @property string                          $description
 * @property bool                            $is_visible
 * @property bool                            $is_displayable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|AttributeValue[] $attributeValues
 * @property-read int|null $attribute_values_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $baseMedia
 * @property-read int|null $base_media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Catalog\Models\ProductCategory[] $categories
 * @property-read int|null $categories_count
 * @property-read string $attribute_values_string
 * @property-read string $categories_string
 * @property-read string $prices_string
 * @property-read \App\Components\Mediable\Models\Media|null $image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $images
 * @property-read int|null $images_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Price[] $prices
 * @property-read int|null $prices_count
 *
 * @method static Builder|Product displayable()
 * @method static \App\Domains\Catalog\Database\Factories\ProductFactory factory(...$parameters)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product orderByCurrentPrice(string $currency, bool $descending)
 * @method static Builder|Product query()
 * @method static Builder|Product search(string $searchable, bool $orderByScore)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereHasAttributeValue(array $attributesValuesByAttributeSlug)
 * @method static Builder|Product whereHasPriceCurrency(string $currency)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereInCategory(\Illuminate\Support\Collection $categories)
 * @method static Builder|Product whereIsDisplayable($value)
 * @method static Builder|Product whereIsVisible($value)
 * @method static Builder|Product wherePriceAbove(\Akaunting\Money\Money $price)
 * @method static Builder|Product wherePriceBelow(\Akaunting\Money\Money $price)
 * @method static Builder|Product wherePriceBetween(?\Akaunting\Money\Money $min, ?\Akaunting\Money\Money $max)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class Product extends Model implements Purchasable, HasMedia, Explored, Exportable
{
    use HasExtendedFunctionality;
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia {
        media as baseMedia;
    }
    use Searchable;

    protected $fillable = [
        'title',
        'description',
    ];

    /*
     * Relations
     * */

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_categories_products', 'product_id', 'category_id')->withTimestamps()->using(ProductProductCategory::class);
    }

    public function attributeValues(): MorphMany
    {
        return $this->morphMany(AttributeValue::class, 'attributable');
    }

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'purchasable');
    }

    public function media(): MorphMany
    {
        return $this->baseMedia()->orderBy('order_column');
    }

    public function images(): MorphMany
    {
        return $this->media()->where('collection_name', ProductMediaCollectionKey::IMAGES->value);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')->where('collection_name', ProductMediaCollectionKey::IMAGES->value);
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

    public function getCategoriesStringAttribute(): string
    {
        return $this->categories->implode(fn (ProductCategory $category): string => $category->title, ',' . PHP_EOL);
    }

    public function getPricesStringAttribute(): string
    {
        return $this->prices->implode(fn (Price $price): string => isset($price->price_discounted) ? $price->price_discounted->render() : $price->price->render(), ',' . PHP_EOL);
    }

    public function getAttributeValuesStringAttribute(): string
    {
        return $this->attributeValues->implode(fn (AttributeValue $value): string => "{$value->attribute->title} - {$value->readable_value}", ',' . PHP_EOL);
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
        $attributes = Attribute::query()->whereIn('slug', array_keys($attributesValuesByAttributeSlug))->get();

        if ($attributes->isNotEmpty()) {
            foreach ($attributes as $attribute) {
                $values = (array) $attributesValuesByAttributeSlug[$attribute->slug];
                if ($attribute->values_type === AttributeValuesType::BOOLEAN) {
                    $values = array_map(static fn (string|int|bool|float $value): bool => Str::of((string) $value)->lower()->toString() === BooleanString::_TRUE->value, $values);
                }

                $query->whereHas('attributeValues', function (Builder $query) use ($attribute, $values): void {
                    $query->where('attribute_id', $attribute->id)->whereIn(AttributeValue::getDatabaseValueColumnByAttributeType($attribute->values_type), $values);
                });
            }
        }
    }

    public function scopeWhereHasPriceCurrency(Builder $query, string $currency): void
    {
        $query->whereHas('prices', fn (Builder $query): Builder => $query->where('currency', $currency));
    }

    public function scopeWherePriceAbove(Builder $query, Money $price): void
    {
        $query->whereHas('prices', fn (Builder $query): Builder => $query->where('currency', $price->getCurrency()->getCurrency())->where(Price::getDatabasePriceExpression(), '>=', $price->getAmount()));
    }

    public function scopeWherePriceBelow(Builder $query, Money $price): void
    {
        $query->whereHas('prices', fn (Builder $query): Builder => $query->where('currency', $price->getCurrency()->getCurrency())->where(Price::getDatabasePriceExpression(), '<=', $price->getAmount()));
    }

    public function scopeWherePriceBetween(Builder|Product $query, ?Money $min, ?Money $max): void
    {
        if (isset($min, $max)) {
            throw_unless($min->getCurrency()->getCurrency() === $max->getCurrency()->getCurrency(), IncompatibleCurrenciesException::class);

            $query->whereHas('prices', fn (Builder $query): Builder => $query->where('currency', $min->getCurrency()->getCurrency())->whereBetween(Price::getDatabasePriceExpression(), $min > $max ? [$max->getAmount(), $min->getAmount()] : [$min->getAmount(), $max->getAmount()]));
        } elseif (isset($min)) {
            $query->wherePriceAbove($min);
        } elseif (isset($max)) {
            $query->wherePriceBelow($max);
        }
    }

    public function scopeOrderByCurrentPrice(Builder $query, string $currency, bool $descending): void
    {
        $query
            ->join('prices', 'prices.purchasable_id', '=', 'products.id')
            ->where('prices.purchasable_type', self::class)
            ->where('prices.currency', $currency)
            ->orderBy(Price::getDatabasePriceExpression(), $descending ? 'DESC' : 'ASC');
    }

    public function scopeDisplayable(Builder|Model $query): void
    {
        $query->where('products.is_displayable', true);
    }

    /*
     * Purchasable
     * */

    public function getPurchasablePrice(string $currency): Money
    {
        /**
         * @var Price $price
         */
        $price = $this->prices->where('currency', $currency)->first();

        return $price->price;
    }

    public function getPurchasablePriceDiscounted(string $currency): ?Money
    {
        /**
         * @var Price $price
         */
        $price = $this->prices->where('currency', $currency)->first();

        return $price->price_discounted;
    }

    public function getPurchasableData(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    /*
     * Searchable
     * */

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
        ];
    }

    public function mappableAs(): array
    {
        return [
            'title' => 'text',
            'slug' => 'text',
            'description' => 'text',
        ];
    }

    /*
     * Exportable
     * */

    public static function getExportJob(): string
    {
        return ProductsExportJob::class;
    }
}
