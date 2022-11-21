<?php

namespace App\Domains\Catalog\Models;

use Akaunting\Money\Money;
use App\Components\Attributable\Models\AttributeValue;
use App\Components\Purchasable\Abstracts\Purchasable;
use App\Components\Purchasable\Models\Price;
use App\Domains\Catalog\Database\Builders\ProductBuilder;
use App\Domains\Catalog\Database\Factories\ProductFactory;
use App\Domains\Catalog\Enums\Media\ProductMediaCollectionKey;
use App\Domains\Catalog\Jobs\Export\ProductsExportJob;
use App\Domains\Catalog\Models\Pivot\ProductProductCategory;
use App\Domains\Generic\Interfaces\Exportable;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use App\Domains\Generic\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|AttributeValue[]                                                        $attributeValues
 * @property-read int|null                                                                                                         $attribute_values_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $baseMedia
 * @property-read int|null                                                                                                         $base_media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Catalog\Models\ProductCategory[]                           $categories
 * @property-read int|null                                                                                                         $categories_count
 * @property-read string                                                                                                           $attribute_values_string
 * @property-read string                                                                                                           $categories_string
 * @property-read string                                                                                                           $prices_string
 * @property-read \App\Components\Mediable\Models\Media|null                                                                       $image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $images
 * @property-read int|null                                                                                                         $images_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\App\Components\Mediable\Models\Media[] $media
 * @property-read int|null                                                                                                         $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Price[]                                                                 $prices
 * @property-read int|null                                                                                                         $prices_count
 *
 * @method static ProductBuilder|Product                                 displayable()
 * @method static \App\Domains\Catalog\Database\Factories\ProductFactory factory(...$parameters)
 * @method static ProductBuilder|Product                                 newModelQuery()
 * @method static ProductBuilder|Product                                 newQuery()
 * @method static ProductBuilder|Product                                 orderByCurrentPrice(string $currency, bool $descending)
 * @method static ProductBuilder|Product                                 query()
 * @method static ProductBuilder|Product                                 search(string $searchable, bool $orderByScore)
 * @method static ProductBuilder|Product                                 whereCreatedAt($value)
 * @method static ProductBuilder|Product                                 whereDescription($value)
 * @method static ProductBuilder|Product                                 whereHasAttributeValue(array $attributesValuesByAttributeSlug)
 * @method static ProductBuilder|Product                                 whereHasPriceCurrency(string $currency)
 * @method static ProductBuilder|Product                                 whereId($value)
 * @method static ProductBuilder|Product                                 whereInCategory(\Illuminate\Support\Collection $categories)
 * @method static ProductBuilder|Product                                 whereIsDisplayable($value)
 * @method static ProductBuilder|Product                                 whereIsVisible($value)
 * @method static ProductBuilder|Product                                 wherePriceAbove(\Akaunting\Money\Money $price)
 * @method static ProductBuilder|Product                                 wherePriceBelow(\Akaunting\Money\Money $price)
 * @method static ProductBuilder|Product                                 wherePriceBetween(?\Akaunting\Money\Money $min, ?\Akaunting\Money\Money $max)
 * @method static ProductBuilder|Product                                 whereSlug($value)
 * @method static ProductBuilder|Product                                 whereTitle($value)
 * @method static ProductBuilder|Product                                 whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class Product extends Model implements Purchasable, HasMedia, Exportable
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
     * Internal
     * */

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function newEloquentBuilder($query): ProductBuilder
    {
        /** @var ProductBuilder<self> $builder */
        $builder = new ProductBuilder($query);

        return $builder;
    }

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
