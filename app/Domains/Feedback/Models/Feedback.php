<?php

namespace App\Domains\Feedback\Models;

use App\Domains\Common\Interfaces\Exportable;
use App\Domains\Common\Traits\Models\HasExtendedFunctionality;
use App\Domains\Common\Traits\Models\Searchable;
use App\Domains\Feedback\Database\Builders\FeedbackBuilder;
use App\Domains\Feedback\Database\Factories\FeedbackFactory;
use App\Domains\Feedback\Jobs\Export\FeedbackExportJob;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
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
 *
 * @property-read User|null $user
 *
 * @method static \App\Domains\Feedback\Database\Factories\FeedbackFactory factory(...$parameters)
 * @method static FeedbackBuilder|Feedback                                 forUser(?string $ip, ?\App\Domains\Users\Models\User $user)
 * @method static FeedbackBuilder|Feedback                                 inLastHour()
 * @method static FeedbackBuilder|Feedback                                 newModelQuery()
 * @method static FeedbackBuilder|Feedback                                 newQuery()
 * @method static FeedbackBuilder|Feedback                                 query()
 * @method static FeedbackBuilder|Feedback                                 search(string $searchable, bool $orderByScore)
 * @method static FeedbackBuilder|Feedback                                 whereCreatedAt($value)
 * @method static FeedbackBuilder|Feedback                                 whereEmail($value)
 * @method static FeedbackBuilder|Feedback                                 whereId($value)
 * @method static FeedbackBuilder|Feedback                                 whereIp($value)
 * @method static FeedbackBuilder|Feedback                                 whereIsReviewed($value)
 * @method static FeedbackBuilder|Feedback                                 wherePhone($value)
 * @method static FeedbackBuilder|Feedback                                 whereText($value)
 * @method static FeedbackBuilder|Feedback                                 whereUserId($value)
 * @method static FeedbackBuilder|Feedback                                 whereUsername($value)
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
     * Internal
     * */

    protected static function newFactory(): FeedbackFactory
    {
        return FeedbackFactory::new();
    }

    public function newEloquentBuilder($query): FeedbackBuilder
    {
        /** @var FeedbackBuilder<self> $builder */
        $builder = new FeedbackBuilder($query);

        return $builder;
    }

    /*
     * Helpers
     * */

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

        /** @var ?self $feedback */
        $feedback = self::query()->forUser($ip, $user)->inLastHour()->select(['created_at'])->latest('created_at')->first();

        return $feedback === null ? null : $feedback->created_at?->addHour()->longAbsoluteDiffForHumans(Carbon::now());
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
