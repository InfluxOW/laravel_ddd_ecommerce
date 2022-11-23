<?php

namespace App\Components\LoginHistoryable\Models;

use App\Components\LoginHistoryable\Database\Builders\LoginHistoryBuilder;
use App\Domains\Common\Traits\Models\HasExtendedFunctionality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use MStaack\LaravelPostgis\Geometries\Point;

/**
 * App\Components\LoginHistoryable\Models\LoginHistory
 *
 * @property int                                           $id
 * @property string                                        $login_historyable_type
 * @property int                                           $login_historyable_id
 * @property string|null                                   $ip
 * @property string|null                                   $user_agent
 * @property string|null                                   $device
 * @property string|null                                   $platform
 * @property string|null                                   $platform_version
 * @property string|null                                   $browser
 * @property string|null                                   $browser_version
 * @property string|null                                   $region_code
 * @property string|null                                   $region_name
 * @property string|null                                   $country_code
 * @property string|null                                   $country_name
 * @property string|null                                   $city
 * @property \MStaack\LaravelPostgis\Geometries\Point|null $location
 * @property string|null                                   $zip
 * @property \Illuminate\Support\Carbon|null               $created_at
 *
 * @property-read Model|\Eloquent $loginHistoryable
 *
 * @method static LoginHistoryBuilder|LoginHistory newModelQuery()
 * @method static LoginHistoryBuilder|LoginHistory newQuery()
 * @method static LoginHistoryBuilder|LoginHistory query()
 * @method static LoginHistoryBuilder|LoginHistory whereBrowser($value)
 * @method static LoginHistoryBuilder|LoginHistory whereBrowserVersion($value)
 * @method static LoginHistoryBuilder|LoginHistory whereCity($value)
 * @method static LoginHistoryBuilder|LoginHistory whereCountryCode($value)
 * @method static LoginHistoryBuilder|LoginHistory whereCountryName($value)
 * @method static LoginHistoryBuilder|LoginHistory whereCreatedAt($value)
 * @method static LoginHistoryBuilder|LoginHistory whereDevice($value)
 * @method static LoginHistoryBuilder|LoginHistory whereId($value)
 * @method static LoginHistoryBuilder|LoginHistory whereIp($value)
 * @method static LoginHistoryBuilder|LoginHistory whereLocation($value)
 * @method static LoginHistoryBuilder|LoginHistory whereLoginHistoryableId($value)
 * @method static LoginHistoryBuilder|LoginHistory whereLoginHistoryableType($value)
 * @method static LoginHistoryBuilder|LoginHistory wherePlatform($value)
 * @method static LoginHistoryBuilder|LoginHistory wherePlatformVersion($value)
 * @method static LoginHistoryBuilder|LoginHistory whereRegionCode($value)
 * @method static LoginHistoryBuilder|LoginHistory whereRegionName($value)
 * @method static LoginHistoryBuilder|LoginHistory whereUserAgent($value)
 * @method static LoginHistoryBuilder|LoginHistory whereZip($value)
 *
 * @mixin \Eloquent
 */
final class LoginHistory extends Model
{
    use HasExtendedFunctionality;
    use PostgisTrait;

    protected $table = 'login_history';

    protected $fillable = [
        'ip',
        'user_agent',
        'device',
        'platform',
        'platform_version',
        'browser',
        'browser_version',
        'region_code',
        'region_name',
        'country_code',
        'country_name',
        'city',
        'location',
        'zip',
    ];

    protected array $postgisFields = [
        'location',
    ];

    public const UPDATED_AT = null;

    /*
     * Internal
     * */

    public function newEloquentBuilder($query): LoginHistoryBuilder
    {
        /** @var LoginHistoryBuilder<self> $builder */
        $builder = new LoginHistoryBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function loginHistoryable(): MorphTo
    {
        return $this->morphTo();
    }

    /*
     * Helpers
     * */

    /**
     * Workaround for IDE helper PHPDocs generation.
     */
    public function getLocationAttribute(): ?Point
    {
        return $this->attributes['location'] ?? null;
    }
}
