<?php

namespace App\Components\Attributable\Models;

use App\Components\Attributable\Database\Builders\AttributeValueBuilder;
use App\Components\Attributable\Database\Factories\AttributeValueFactory;
use App\Components\Attributable\Enums\AttributeValuesType;
use App\Domains\Common\Traits\Models\HasExtendedFunctionality;
use App\Domains\Common\Utils\StringUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Components\Attributable\Models\AttributeValue
 *
 * @property int                             $id
 * @property string                          $attributable_type
 * @property int                             $attributable_id
 * @property int                             $attribute_id
 * @property string|null                     $value_string
 * @property int|null                        $value_integer
 * @property bool|null                       $value_boolean
 * @property float|null                      $value_float
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read Model|\Eloquent                               $attributable
 * @property-read \App\Components\Attributable\Models\Attribute $attribute
 * @property-read string                                        $readable_value
 *
 * @property string|int|float|bool $value
 *
 * @method static \App\Components\Attributable\Database\Factories\AttributeValueFactory factory(...$parameters)
 * @method static AttributeValueBuilder|AttributeValue                                  newModelQuery()
 * @method static AttributeValueBuilder|AttributeValue                                  newQuery()
 * @method static AttributeValueBuilder|AttributeValue                                  query()
 * @method static AttributeValueBuilder|AttributeValue                                  whereAttributableId($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereAttributableType($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereAttributeId($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereCreatedAt($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereId($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereUpdatedAt($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereValueBoolean($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereValueFloat($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereValueInteger($value)
 * @method static AttributeValueBuilder|AttributeValue                                  whereValueString($value)
 *
 * @mixin \Eloquent
 */
final class AttributeValue extends Model
{
    use HasExtendedFunctionality;
    use HasFactory;

    protected const VALUES_COLUMNS = [
        'value_string',
        'value_boolean',
        'value_float',
        'value_integer',
    ];

    protected $casts = [
        'value_float' => 'float',
    ];

    protected $fillable = [
        'value_string',
        'value_boolean',
        'value_float',
        'value_integer',
        'attribute_id',
    ];

    /*
     * Internal
     * */

    protected static function newFactory(): AttributeValueFactory
    {
        return AttributeValueFactory::new();
    }

    public function newEloquentBuilder($query): AttributeValueBuilder
    {
        /** @var AttributeValueBuilder<self> $builder */
        $builder = new AttributeValueBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributable(): MorphTo
    {
        return $this->morphTo();
    }

    /*
     * Attributes
     * */

    public function getValueAttribute(): string|int|bool|float
    {
        return $this->{self::getDatabaseValueColumnByAttributeType($this->attribute->values_type)};
    }

    public function setValueAttribute(string|int|bool|float $value): void
    {
        foreach (self::VALUES_COLUMNS as $column) {
            $this->$column = null;
        }

        $this->{self::getDatabaseValueColumnByAttributeType($this->attribute->values_type)} = $value;
    }

    public function getReadableValueAttribute(): string
    {
        $value = $this->{self::getDatabaseValueColumnByAttributeType($this->attribute->values_type)};

        if ($this->attribute->values_type === AttributeValuesType::BOOLEAN) {
            return StringUtils::boolToString($value);
        }

        return $value;
    }

    /*
     * Helpers
     * */

    public static function getDatabaseValueColumnByAttributeType(AttributeValuesType $attributeValuesType): string
    {
        return match ($attributeValuesType) {
            AttributeValuesType::BOOLEAN => 'value_boolean',
            AttributeValuesType::INTEGER => 'value_integer',
            AttributeValuesType::FLOAT => 'value_float',
            AttributeValuesType::STRING => 'value_string',
        };
    }
}
