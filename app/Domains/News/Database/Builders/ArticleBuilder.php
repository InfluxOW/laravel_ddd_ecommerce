<?php

namespace App\Domains\News\Database\Builders;

use App\Domains\News\Models\Article;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Article
 *
 * @uses Model
 *
 * @template TModelClass of Model
 *
 * @extends Builder<TModelClass>
 * */
final class ArticleBuilder extends Builder
{
    public function published(): self
    {
        $this->wherePublishedBefore(Carbon::now());

        return $this;
    }

    public function unpublished(): self
    {
        $this->whereNull('published_at')->orWhere(fn (self $query) => $query->wherePublishedAfter(Carbon::now()));

        return $this;
    }

    public function wherePublishedAfter(Carbon $date): self
    {
        $this->where('published_at', '>=', $date);

        return $this;
    }

    public function wherePublishedBefore(Carbon $date): self
    {
        $this->where('published_at', '<=', $date);

        return $this;
    }

    public function wherePublishedBetween(?Carbon $min, ?Carbon $max): self
    {
        if (isset($min, $max) && $min > $max) {
            [$max, $min] = [$min, $max];
        }

        if (isset($min)) {
            $this->wherePublishedAfter($min);
        }

        if (isset($max)) {
            $this->wherePublishedBefore($max);
        }

        return $this;
    }
}
