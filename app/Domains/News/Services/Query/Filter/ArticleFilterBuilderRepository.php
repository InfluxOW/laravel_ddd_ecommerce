<?php

namespace App\Domains\News\Services\Query\Filter;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

final class ArticleFilterBuilderRepository
{
    public function getMinPublishedAt(SpatieQueryBuilder $newsQuery): ?Carbon
    {
        return $this->date(DB::table('news')->whereIn('id', $newsQuery->getQuery()->select(['news.id']))->min('published_at'));
    }

    public function getMaxPublishedAt(SpatieQueryBuilder $newsQuery): ?Carbon
    {
        return $this->date(DB::table('news')->whereIn('id', $newsQuery->getQuery()->select(['news.id']))->max('published_at'));
    }

    private function date(?string $date): ?Carbon
    {
        if ($date === null) {
            return null;
        }

        /** @var ?Carbon $carbon */
        $carbon = Carbon::createFromFormat(app('db.connection')->getQueryGrammar()->getDateFormat(), $date);

        return $carbon;
    }
}
