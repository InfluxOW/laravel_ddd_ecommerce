<?php

namespace App\Components\Mediable\Models;

use App\Components\Mediable\Classes\RegisteredResponsiveImages;
use App\Components\Mediable\Database\Builders\MediaBuilder;
use App\Components\Mediable\Enums\MediaType;
use App\Domains\Common\Traits\Models\HasExtendedFunctionality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * App\Components\Mediable\Models\Media
 *
 * @property int         $id
 * @property string      $model_type
 * @property int         $model_id
 * @property string|null $uuid
 * @property string      $collection_name
 * @property string      $name
 * @property string      $file_name
 * @property string|null $mime_type
 * @property string      $disk
 * @property string|null $conversions_disk
 * @property int         $size
 * @property array       $manipulations
 * @property array       $custom_properties
 * @property array       $generated_conversions
 * @property array       $responsive_images
 * @property int|null    $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read string          $type
 * @property-read Model|\Eloquent $model
 *
 * @method static MediaCollection|static[] all($columns = ['*'])
 * @method static MediaCollection|static[] get($columns = ['*'])
 * @method static MediaBuilder|Media       newModelQuery()
 * @method static MediaBuilder|Media       newQuery()
 * @method static MediaBuilder|Media       ordered()
 * @method static MediaBuilder|Media       query()
 * @method static MediaBuilder|Media       whereCollectionName($value)
 * @method static MediaBuilder|Media       whereConversionsDisk($value)
 * @method static MediaBuilder|Media       whereCreatedAt($value)
 * @method static MediaBuilder|Media       whereCustomProperties($value)
 * @method static MediaBuilder|Media       whereDisk($value)
 * @method static MediaBuilder|Media       whereFileName($value)
 * @method static MediaBuilder|Media       whereGeneratedConversions($value)
 * @method static MediaBuilder|Media       whereId($value)
 * @method static MediaBuilder|Media       whereManipulations($value)
 * @method static MediaBuilder|Media       whereMimeType($value)
 * @method static MediaBuilder|Media       whereModelId($value)
 * @method static MediaBuilder|Media       whereModelType($value)
 * @method static MediaBuilder|Media       whereName($value)
 * @method static MediaBuilder|Media       whereOrderColumn($value)
 * @method static MediaBuilder|Media       whereResponsiveImages($value)
 * @method static MediaBuilder|Media       whereSize($value)
 * @method static MediaBuilder|Media       whereUpdatedAt($value)
 * @method static MediaBuilder|Media       whereUuid($value)
 *
 * @mixin \Eloquent
 */
final class Media extends BaseMedia
{
    use HasExtendedFunctionality;

    /*
     * Internal
     * */

    public function newEloquentBuilder($query): MediaBuilder
    {
        /** @var MediaBuilder<self> $builder */
        $builder = new MediaBuilder($query);

        return $builder;
    }

    /*
     * Attributes
     * */

    public function getTypeAttribute(): string
    {
        $parentGetType = $this->type()->get;

        return (MediaType::tryFrom($parentGetType()) ?? MediaType::OTHER)->name;
    }

    /*
     * Helpers
     * */

    public function responsiveImages(string $conversionName = ''): RegisteredResponsiveImages
    {
        return new RegisteredResponsiveImages($this, $conversionName);
    }
}
