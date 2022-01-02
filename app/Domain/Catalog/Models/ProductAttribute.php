<?php

namespace App\Domain\Catalog\Models;

use App\Domain\Catalog\Database\Factories\ProductAttributeFactory;
use App\Domain\Catalog\Enums\ProductAttributeValuesType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\ProductAttribute
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property ProductAttributeValuesType $values_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \App\Domain\Catalog\Database\Factories\ProductAttributeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereValuesType($value)
 * @mixin \Eloquent
 */
class ProductAttribute extends Model
{
    use HasFactory;
    use HasSlug;

    protected $casts = [
        'values_type' => ProductAttributeValuesType::class,
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
     * Helpers
     * */

    protected static function newFactory(): ProductAttributeFactory
    {
        return ProductAttributeFactory::new();
    }
}
