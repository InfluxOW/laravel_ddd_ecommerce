<?php

namespace App\Components\Attributable\Models;

use App\Components\Attributable\Database\Factories\AttributeValueFactory;
use App\Components\Attributable\Enums\AttributeValuesType;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use App\Domains\Generic\Utils\StringUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Domains\Catalog\Models\ProductAttributeValue
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
 * @property-read Model|\Eloquent $attributable
 * @property-read \App\Components\Attributable\Models\Attribute $attribute
 * @property-read string $readable_value
 * @property string|int|float|bool $value
 *
 * @method static \App\Components\Attributable\Database\Factories\AttributeValueFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereAttributableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereAttributableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereValueBoolean($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereValueFloat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereValueInteger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeValue whereValueString($value)
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

    protected static function newFactory(): AttributeValueFactory
    {
        return AttributeValueFactory::new();
    }
}
