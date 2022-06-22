<?php

namespace App\Domains\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\Users\Models\LoginHistory
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $device
 * @property string|null $platform
 * @property string|null $platform_version
 * @property string|null $browser
 * @property string|null $browser_version
 * @property string|null $ip
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $region_code
 * @property string|null $region_name
 * @property string|null $country_code
 * @property string|null $country_name
 * @property string|null $city
 * @property string|null $zip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \App\Domains\Users\Database\Factories\LoginHistoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereBrowserVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereCountryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory wherePlatformVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereRegionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereZip($value)
 * @mixin \Eloquent
 */
final class LoginHistory extends Model
{
    protected $table = 'login_history';

    protected $fillable = [
        'ip',
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
        'latitude',
        'longitude',
        'zip',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
