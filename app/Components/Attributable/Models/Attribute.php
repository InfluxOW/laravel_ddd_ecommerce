<?php

namespace App\Components\Attributable\Models;

use App\Components\Attributable\Database\Builders\AttributeBuilder;
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
 * @method static AttributeBuilder|Attribute                                       newModelQuery()
 * @method static AttributeBuilder|Attribute                                       newQuery()
 * @method static AttributeBuilder|Attribute                                       query()
 * @method static AttributeBuilder|Attribute                                       whereCreatedAt($value)
 * @method static AttributeBuilder|Attribute                                       whereId($value)
 * @method static AttributeBuilder|Attribute                                       whereSlug($value)
 * @method static AttributeBuilder|Attribute                                       whereTitle($value)
 * @method static AttributeBuilder|Attribute                                       whereUpdatedAt($value)
 * @method static AttributeBuilder|Attribute                                       whereValuesType($value)
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
     * Internal
     * */

    protected static function newFactory(): AttributeFactory
    {
        return AttributeFactory::new();
    }

    public function newEloquentBuilder($query): AttributeBuilder
    {
        /** @var AttributeBuilder<self> $builder */
        $builder = new AttributeBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
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
}
