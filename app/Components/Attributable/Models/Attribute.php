<?php

namespace App\Components\Attributable\Models;

use App\Components\Attributable\Database\Factories\AttributeFactory;
use App\Components\Attributable\Enums\AttributeValuesType;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Components\Attributable\Models\Attribute
 *
 * @property int                             $id
 * @property string                          $title
 * @property string                          $slug
 * @property AttributeValuesType             $values_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Components\Attributable\Models\AttributeValue[] $values
 * @property-read int|null $values_count
 *
 * @method static \App\Components\Attributable\Database\Factories\AttributeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute                  whereValuesType($value)
 *
 * @mixin \Eloquent
 */
final class Attribute extends Model
{
    use HasExtendedFunctionality;
    use HasFactory;
    use HasSlug;

    protected $casts = [
        'values_type' => AttributeValuesType::class,
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
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    /*
     * Helpers
     * */

    protected static function newFactory(): AttributeFactory
    {
        return AttributeFactory::new();
    }
}
