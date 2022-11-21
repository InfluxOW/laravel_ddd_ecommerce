<?php

namespace App\Components\Addressable\Models;

use App\Components\Addressable\Database\Builders\AddressBuilder;
use App\Components\Addressable\Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Squire\Models\Country;
use Squire\Models\Region;

/**
 * App\Components\Addressable\Models\Address
 *
 * @property int                             $id
 * @property string                          $addressable_type
 * @property int                             $addressable_id
 * @property string                          $zip
 * @property string                          $country
 * @property string|null                     $region
 * @property string                          $city
 * @property string                          $street
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $addressable
 * @property-read string $string_representation
 *
 * @method static \App\Components\Addressable\Database\Factories\AddressFactory factory(...$parameters)
 * @method static AddressBuilder|Address                                        newModelQuery()
 * @method static AddressBuilder|Address                                        newQuery()
 * @method static AddressBuilder|Address                                        query()
 * @method static AddressBuilder|Address                                        whereAddressableId($value)
 * @method static AddressBuilder|Address                                        whereAddressableType($value)
 * @method static AddressBuilder|Address                                        whereCity($value)
 * @method static AddressBuilder|Address                                        whereCountry($value)
 * @method static AddressBuilder|Address                                        whereCreatedAt($value)
 * @method static AddressBuilder|Address                                        whereId($value)
 * @method static AddressBuilder|Address                                        whereRegion($value)
 * @method static AddressBuilder|Address                                        whereStreet($value)
 * @method static AddressBuilder|Address                                        whereUpdatedAt($value)
 * @method static AddressBuilder|Address                                        whereZip($value)
 *
 * @mixin \Eloquent
 */
final class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'zip',
        'country',
        'region',
        'city',
        'street',
    ];

    /*
     * Internal
     * */

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }

    public function newEloquentBuilder($query): AddressBuilder
    {
        /** @var AddressBuilder<self> $builder */
        $builder = new AddressBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    /*
     * Attributes
     * */

    public function getStringRepresentationAttribute(): string
    {
        return (string) $this;
    }

    /*
     * Getters
     * */

    public function getCountry(): ?Country
    {
        return Country::query()->find($this->country);
    }

    public function getRegion(): ?Region
    {
        return $this->region === null ? null : Region::query()->find($this->region);
    }

    /*
     * Helpers
     * */

    public function __toString(): string
    {
        return $this->region === null ? sprintf('%s, %s, %s, %s', $this->zip, $this->getCountry()?->name, $this->city, $this->street) : sprintf('%s, %s, %s, %s, %s', $this->zip, $this->getCountry()?->name, $this->getRegion()?->name, $this->city, $this->street);
    }
}
