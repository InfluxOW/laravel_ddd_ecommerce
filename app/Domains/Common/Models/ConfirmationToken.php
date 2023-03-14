<?php

namespace App\Domains\Common\Models;

use App\Domains\Common\Database\Builders\ConfirmationTokenBuilder;
use App\Domains\Common\Traits\Models\HasExtendedFunctionality;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\Common\Models\ConfirmationToken
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property string                          $type
 * @property string                          $token
 * @property \Illuminate\Support\Carbon      $expires_at
 * @property \Illuminate\Support\Carbon|null $used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read User $user
 *
 * @method static ConfirmationTokenBuilder|ConfirmationToken expired()
 * @method static ConfirmationTokenBuilder|ConfirmationToken newModelQuery()
 * @method static ConfirmationTokenBuilder|ConfirmationToken newQuery()
 * @method static ConfirmationTokenBuilder|ConfirmationToken query()
 * @method static ConfirmationTokenBuilder|ConfirmationToken unexpired()
 * @method static ConfirmationTokenBuilder|ConfirmationToken unused()
 * @method static ConfirmationTokenBuilder|ConfirmationToken whereCreatedAt($value)
 * @method static ConfirmationTokenBuilder|ConfirmationToken whereExpiresAt($value)
 * @method static ConfirmationTokenBuilder|ConfirmationToken whereId($value)
 * @method static ConfirmationTokenBuilder|ConfirmationToken whereToken($value)
 * @method static ConfirmationTokenBuilder|ConfirmationToken whereType($value)
 * @method static ConfirmationTokenBuilder|ConfirmationToken whereUpdatedAt($value)
 * @method static ConfirmationTokenBuilder|ConfirmationToken whereUsedAt($value)
 * @method static ConfirmationTokenBuilder|ConfirmationToken whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class ConfirmationToken extends Model
{
    use HasExtendedFunctionality;

    protected $fillable = [
        'type',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = ['expires_at' => 'datetime', 'used_at' => 'datetime'];

    /*
     * Internal
     * */

    public function newEloquentBuilder($query): ConfirmationTokenBuilder
    {
        /** @var ConfirmationTokenBuilder<self> $builder */
        $builder = new ConfirmationTokenBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
     * Helpers
     * */

    public function isExpired(): bool
    {
        return $this->expires_at <= Carbon::now();
    }

    public function isUsed(): bool
    {
        return isset($this->used_at);
    }
}
