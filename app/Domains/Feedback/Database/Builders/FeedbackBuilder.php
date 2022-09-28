<?php

namespace App\Domains\Feedback\Database\Builders;

use App\Domains\Feedback\Models\Feedback;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Feedback
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class FeedbackBuilder extends Builder
{
    public function forUser(?string $ip, ?User $user): self
    {
        $this->where(function (self $query) use ($ip, $user): void {
            if (isset($ip)) {
                $query->where('ip', $ip);
            }

            if (isset($user)) {
                $query->orWhereBelongsTo($user, 'user');
            }
        });

        return $this;
    }

    public function inLastHour(): self
    {
        $this->where('created_at', '>', Carbon::now()->subHour());

        return $this;
    }
}
