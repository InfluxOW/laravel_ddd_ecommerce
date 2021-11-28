<?php

namespace App\Domain\Products\Models;

use App\Domain\Products\Database\Factories\ProductAttributeValueFactory;
use App\Domain\Products\Enums\ProductAttributeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ProductAttributeValue
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
 * @property-read \App\Domain\Products\Models\ProductAttribute $attribute
 * @property mixed|null $value
 * @property-read \App\Domain\Products\Models\Product $product
 * @method static \App\Domain\Products\Database\Factories\ProductAttributeValueFactory factory(...$parameters)
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
class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $casts = [
        'value_float' => 'float',
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

    public function getValueAttribute(): mixed
    {
        return $this->{self::getDatabaseValueColumnByAttributeType($this->attribute->type)};
    }

    public function setValueAttribute(mixed $value): void
    {
        $this->{self::getDatabaseValueColumnByAttributeType($this->attribute->type)} = $value;
    }

    /*
     * Helpers
     * */

    public static function getDatabaseValueColumnByAttributeType(ProductAttributeType $attributeType): string
    {
        return match ($attributeType) {
            ProductAttributeType::BOOLEAN => 'value_boolean',
            ProductAttributeType::INTEGER => 'value_integer',
            ProductAttributeType::FLOAT => 'value_float',
            ProductAttributeType::STRING => 'value_string',
        };
    }

    protected static function newFactory(): ProductAttributeValueFactory
    {
        return ProductAttributeValueFactory::new();
    }
}
