<?php

namespace App\Domains\Common\Database\Builders;

use App\Domains\Common\Models\ConfirmationToken;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin ConfirmationToken
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class ConfirmationTokenBuilder extends Builder
{
    public function unexpired(): self
    {
        $this->where('expires_at', '>', Carbon::now());

        return $this;
    }

    public function expired(): self
    {
        $this->where('expires_at', '<=', Carbon::now());

        return $this;
    }

    public function unused(): self
    {
        $this->whereNull('used_at');

        return $this;
    }
}
