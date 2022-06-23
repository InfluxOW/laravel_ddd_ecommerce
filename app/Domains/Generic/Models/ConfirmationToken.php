<?php

namespace App\Domains\Generic\Models;

use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\Generic\Models\ConfirmationToken
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $token
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|ConfirmationToken expired()
 * @method static Builder|ConfirmationToken newModelQuery()
 * @method static Builder|ConfirmationToken newQuery()
 * @method static Builder|ConfirmationToken query()
 * @method static Builder|ConfirmationToken unexpired()
 * @method static Builder|ConfirmationToken unused()
 * @method static Builder|ConfirmationToken whereCreatedAt($value)
 * @method static Builder|ConfirmationToken whereExpiresAt($value)
 * @method static Builder|ConfirmationToken whereId($value)
 * @method static Builder|ConfirmationToken whereToken($value)
 * @method static Builder|ConfirmationToken whereType($value)
 * @method static Builder|ConfirmationToken whereUpdatedAt($value)
 * @method static Builder|ConfirmationToken whereUsedAt($value)
 * @method static Builder|ConfirmationToken whereUserId($value)
 * @mixin \Eloquent
 */
final class ConfirmationToken extends Model
{
    protected $fillable = [
        'type',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $dates = [
        'expires_at',
        'used_at',
    ];

    /*
     * Relations
     * */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
     * Scopes
     * */

    public function scopeUnexpired(Builder $query): void
    {
        $query->where('expires_at', '>', Carbon::now());
    }

    public function scopeExpired(Builder $query): void
    {
        $query->where('expires_at', '<=', Carbon::now());
    }

    public function scopeUnused(Builder $query): void
    {
        $query->whereNull('used_at');
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
