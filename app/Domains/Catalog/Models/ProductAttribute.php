<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Database\Factories\ProductAttributeFactory;
use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use App\Domains\Generic\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JeroenG\Explorer\Application\Explored;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Domains\Catalog\Models\ProductAttribute
 *
 * @property int                             $id
 * @property string                          $title
 * @property string                          $slug
 * @property ProductAttributeValuesType      $values_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Catalog\Models\ProductAttributeValue[] $values
 * @property-read int|null $values_count
 *
 * @method static \App\Domains\Catalog\Database\Factories\ProductAttributeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute search(string $searchable, bool $orderByScore)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereValuesType($value)
 * @mixin \Eloquent
 */
final class ProductAttribute extends Model implements Explored
{
    use HasExtendedFunctionality;
    use HasFactory;
    use HasSlug;
    use Searchable;

    protected $casts = [
        'values_type' => ProductAttributeValuesType::class,
    ];

    protected $fillable = [
        'title',
        'slug',
        'values_type',
    ];

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
     * Relations
     * */

    public function values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_id');
    }

    /*
     * Helpers
     * */

    protected static function newFactory(): ProductAttributeFactory
    {
        return ProductAttributeFactory::new();
    }

    /*
     * Searchable
     * */

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }

    public function mappableAs(): array
    {
        return [
            'title' => 'text',
            'slug' => 'text',
        ];
    }
}
