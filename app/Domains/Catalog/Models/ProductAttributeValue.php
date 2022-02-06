<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Database\Factories\ProductAttributeValueFactory;
use App\Domains\Catalog\Enums\ProductAttributeValuesType;
use App\Domains\Generic\Utils\StringUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\Catalog\Models\ProductAttributeValue
 *
 * @property int $id
 * @property int $product_id
 * @property int $attribute_id
 * @property string|null $value_string
 * @property int|null $value_integer
 * @property bool|null $value_boolean
 * @property float|null $value_float
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Domains\Catalog\Models\ProductAttribute $attribute
 * @property-read string $readable_value
 * @property string|int|float|bool $value
 * @property-read \App\Domains\Catalog\Models\Product $product
 * @method static \App\Domains\Catalog\Database\Factories\ProductAttributeValueFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereValueBoolean($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereValueFloat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereValueInteger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttributeValue whereValueString($value)
 * @mixin \Eloquent
 */
final class ProductAttributeValue extends Model
{
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
        return $this->belongsTo(ProductAttribute::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /*
     * Attributes
     * */

    public function getValueAttribute(): string|int|bool|float
    {
        return $this->{self::getDatabaseValueColumnByAttributeType($this->attribute->values_type)};
    }

    public function getReadableValueAttribute(): string
    {
        $value = $this->{self::getDatabaseValueColumnByAttributeType($this->attribute->values_type)};

        if ($this->attribute->values_type === ProductAttributeValuesType::BOOLEAN) {
            return StringUtils::boolToString($value);
        }

        return $value;
    }

    public function setValueAttribute(string|int|bool|float $value): void
    {
        foreach (self::VALUES_COLUMNS as $column) {
            $this->$column = null;
        }

        $this->{self::getDatabaseValueColumnByAttributeType($this->attribute->values_type)} = $value;
    }

    /*
     * Helpers
     * */

    public static function getDatabaseValueColumnByAttributeType(ProductAttributeValuesType $attributeValuesType): string
    {
        return match ($attributeValuesType) {
            ProductAttributeValuesType::BOOLEAN => 'value_boolean',
            ProductAttributeValuesType::INTEGER => 'value_integer',
            ProductAttributeValuesType::FLOAT => 'value_float',
            ProductAttributeValuesType::STRING => 'value_string',
        };
    }

    protected static function newFactory(): ProductAttributeValueFactory
    {
        return ProductAttributeValueFactory::new();
    }
}
