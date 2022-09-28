<?php

namespace App\Domains\Users\Models;

use App\Components\Addressable\Models\Address;
use App\Components\LoginHistoryable\Models\LoginHistory;
use App\Domains\Cart\Models\Cart;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Generic\Interfaces\Exportable;
use App\Domains\Generic\Models\ConfirmationToken;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use App\Domains\Generic\Traits\Models\Searchable;
use App\Domains\Users\Database\Builders\UserBuilder;
use App\Domains\Users\Database\Factories\UserFactory;
use App\Domains\Users\Jobs\Export\UsersExportJob;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Domains\Users\Models\User
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $email
 * @property string|null                     $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string                          $password
 * @property string|null                     $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ConfirmationToken[] $confirmationTokens
 * @property-read int|null $confirmation_tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Feedback[] $feedback
 * @property-read int|null $feedback_count
 * @property-read bool $has_verified_email
 * @property-read \Carbon\Carbon|null $last_logged_in_at
 * @property-read \Illuminate\Database\Eloquent\Collection|LoginHistory[] $loginHistory
 * @property-read int|null $login_history_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \App\Domains\Users\Database\Factories\UserFactory factory(...$parameters)
 * @method static UserBuilder|User                                  newModelQuery()
 * @method static UserBuilder|User                                  newQuery()
 * @method static UserBuilder|User                                  query()
 * @method static UserBuilder|User                                  search(string $searchable, bool $orderByScore)
 * @method static UserBuilder|User                                  whereCreatedAt($value)
 * @method static UserBuilder|User                                  whereEmail($value)
 * @method static UserBuilder|User                                  whereEmailVerifiedAt($value)
 * @method static UserBuilder|User                                  whereId($value)
 * @method static UserBuilder|User                                  whereName($value)
 * @method static UserBuilder|User                                  wherePassword($value)
 * @method static UserBuilder|User                                  wherePhone($value)
 * @method static UserBuilder|User                                  whereRememberToken($value)
 * @method static UserBuilder|User                                  whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class User extends Authenticatable implements MustVerifyEmail, Exportable
{
    use HasExtendedFunctionality;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
 * Internal
 * */

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function newEloquentBuilder($query): UserBuilder
    {
        /** @var UserBuilder<self> $builder */
        $builder = new UserBuilder($query);

        return $builder;
    }

    /*
     * Relations
     * */

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function confirmationTokens(): HasMany
    {
        return $this->hasMany(ConfirmationToken::class);
    }

    public function loginHistory(): MorphMany
    {
        return $this->morphMany(LoginHistory::class, 'login_historyable');
    }

    /*
     * Attributes
     * */

    public function getLastLoggedInAtAttribute(): ?Carbon
    {
        return $this->loginHistory->last()?->created_at;
    }

    public function getHasVerifiedEmailAttribute(): bool
    {
        return isset($this->email_verified_at);
    }

    /*
     * Searchable
     * */

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }

    /*
     * Exportable
     * */

    public static function getExportJob(): string
    {
        return UsersExportJob::class;
    }
}
