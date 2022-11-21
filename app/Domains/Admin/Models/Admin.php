<?php

namespace App\Domains\Admin\Models;

use App\Components\LoginHistoryable\Models\LoginHistory;
use App\Domains\Admin\Database\Builders\AdminBuilder;
use App\Domains\Admin\Database\Factories\AdminFactory;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Domains\Admin\Models\Admin
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $email
 * @property string                          $password
 * @property string|null                     $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|LoginHistory[]                                                   $loginHistory
 * @property-read int|null                                                                                                  $login_history_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null                                                                                                  $notifications_count
 *
 * @method static \App\Domains\Admin\Database\Factories\AdminFactory factory(...$parameters)
 * @method static AdminBuilder|Admin                                 newModelQuery()
 * @method static AdminBuilder|Admin                                 newQuery()
 * @method static AdminBuilder|Admin                                 query()
 * @method static AdminBuilder|Admin                                 whereCreatedAt($value)
 * @method static AdminBuilder|Admin                                 whereEmail($value)
 * @method static AdminBuilder|Admin                                 whereId($value)
 * @method static AdminBuilder|Admin                                 whereName($value)
 * @method static AdminBuilder|Admin                                 wherePassword($value)
 * @method static AdminBuilder|Admin                                 whereRememberToken($value)
 * @method static AdminBuilder|Admin                                 whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class Admin extends Authenticatable implements FilamentUser
{
    use HasExtendedFunctionality;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
     * Internal
     * */

    protected static function newFactory(): AdminFactory
    {
        return AdminFactory::new();
    }

    public function newEloquentBuilder($query): AdminBuilder
    {
        /** @var AdminBuilder<self> $builder */
        $builder = new AdminBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function loginHistory(): MorphMany
    {
        return $this->morphMany(LoginHistory::class, 'login_historyable');
    }

    /*
     * Filament
     * */

    public function canAccessFilament(): bool
    {
        return true;
    }
}
