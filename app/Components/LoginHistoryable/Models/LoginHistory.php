<?php

namespace App\Components\LoginHistoryable\Models;

use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
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
 * @property-read Model|\Eloquent $loginHistoryable
 *
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory newModelQuery()
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory newQuery()
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory query()
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereBrowser($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereBrowserVersion($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereCity($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereCountryCode($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereCountryName($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereCreatedAt($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereDevice($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereId($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereIp($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereLocation($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereLoginHistoryableId($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereLoginHistoryableType($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory wherePlatform($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory wherePlatformVersion($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereRegionCode($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereRegionName($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereUserAgent($value)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|LoginHistory whereZip($value)
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
