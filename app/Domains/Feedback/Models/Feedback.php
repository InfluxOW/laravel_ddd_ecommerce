<?php

namespace App\Domains\Feedback\Models;

use App\Domains\Feedback\Database\Factories\FeedbackFactory;
use App\Domains\Feedback\Jobs\Export\FeedbackExportJob;
use App\Domains\Generic\Interfaces\Exportable;
use App\Domains\Generic\Traits\Models\HasExtendedFunctionality;
use App\Domains\Generic\Traits\Models\Searchable;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\Feedback\Models\Feedback
 *
 * @property int                             $id
 * @property int|null                        $user_id
 * @property string                          $username
 * @property string                          $email
 * @property string|null                     $phone
 * @property string                          $text
 * @property bool                            $is_reviewed
 * @property string|null                     $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read User|null $user
 *
 * @method static \App\Domains\Feedback\Database\Factories\FeedbackFactory factory(...$parameters)
 * @method static Builder|Feedback                                         forUser(?string $ip, ?\App\Domains\Users\Models\User $user)
 * @method static Builder|Feedback                                         inLastHour()
 * @method static Builder|Feedback                                         newModelQuery()
 * @method static Builder|Feedback                                         newQuery()
 * @method static Builder|Feedback                                         query()
 * @method static Builder|Feedback                                         search(string $searchable, bool $orderByScore)
 * @method static Builder|Feedback                                         whereCreatedAt($value)
 * @method static Builder|Feedback                                         whereEmail($value)
 * @method static Builder|Feedback                                         whereId($value)
 * @method static Builder|Feedback                                         whereIp($value)
 * @method static Builder|Feedback                                         whereIsReviewed($value)
 * @method static Builder|Feedback                                         wherePhone($value)
 * @method static Builder|Feedback                                         whereText($value)
 * @method static Builder|Feedback                                         whereUserId($value)
 * @method static Builder|Feedback                                         whereUsername($value)
 *
 * @mixin \Eloquent
 */
final class Feedback extends Model implements Exportable
{
    use HasExtendedFunctionality;
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'username',
        'email',
        'phone',
        'text',
    ];

    public const UPDATED_AT = null;

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

    protected static function newFactory(): FeedbackFactory
    {
        return FeedbackFactory::new();
    }

    public static function canBeCreated(?int $limitPerHour, ?string $ip, ?User $user): bool
    {
        if ($limitPerHour === null) {
            return true;
        }

        if ($ip === null && $user === null) {
            return false;
        }

        return self::query()->forUser($ip, $user)->inLastHour()->count() < $limitPerHour;
    }

    public static function getTimeStringDecayBeforeNextFeedback(?string $ip, ?User $user): ?string
    {
        if ($ip === null && $user === null) {
            return Carbon::now()->longAbsoluteDiffForHumans(Carbon::now()->subHour());
        }

        $feedback = self::query()->select(['created_at'])->forUser($ip, $user)->inLastHour()->latest('created_at')->first();

        return ($feedback === null) ? null : $feedback->created_at?->addHour()->longAbsoluteDiffForHumans(Carbon::now());
    }

    /*
     * Scopes
     * */

    public function scopeForUser(Builder $query, ?string $ip, ?User $user): void
    {
        $query->where(function (Builder $query) use ($ip, $user): void {
            if (isset($ip)) {
                $query->where('ip', $ip);
            }

            if (isset($user)) {
                $query->orWhereBelongsTo($user, 'user');
            }
        });
    }

    public function scopeInLastHour(Builder $query): void
    {
        $query->where('created_at', '>', Carbon::now()->subHour());
    }

    /*
     * Searchable
     * */

    public function toSearchableArray(): array
    {
        return [
            'text' => $this->text,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }

    /*
     * Exportable
     * */

    public static function getExportJob(): string
    {
        return FeedbackExportJob::class;
    }
}
