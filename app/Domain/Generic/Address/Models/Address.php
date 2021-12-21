<?php

namespace App\Domain\Generic\Address\Models;

use App\Domain\Generic\Address\Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Squire\Models\Country;
use Squire\Models\Region;

/**
 * App\Domain\Generic\Address\Models\Address
 *
 * @property int $id
 * @property string $addressable_type
 * @property int $addressable_id
 * @property string $zip
 * @property string $country
 * @property string|null $region
 * @property string $city
 * @property string $street
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $addressable
 * @method static \App\Domain\Generic\Address\Database\Factories\AddressFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddressableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddressableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereZip($value)
 * @mixin \Eloquent
 */
class Address extends Model
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
     * Relations
     * */

    public function addressable(): MorphTo
    {
        return $this->morphTo();
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
        return ($this->region === null) ? null : Region::query()->find($this->region);
    }

    /*
     * Helpers
     * */

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }

    public function __toString()
    {
        return ($this->region === null) ? sprintf('%s, %s, %s, %s', $this->zip, $this->getCountry()?->name, $this->city, $this->street) : sprintf('%s, %s, %s, %s, %s', $this->zip, $this->getCountry()?->name, $this->getRegion()?->name, $this->city, $this->street);
    }
}
