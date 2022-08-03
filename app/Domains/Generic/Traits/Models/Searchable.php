<?php

namespace App\Domains\Generic\Traits\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use JeroenG\Explorer\Domain\Syntax\QueryString;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Searchable as BaseSearchable;

trait Searchable
{
    use BaseSearchable {
        search as baseSearch;
    }

    protected int $maxSearchResultsCount = 100;

    public static function search(string $query = '', ?Closure $callback = null): ScoutBuilder
    {
        /** @var string $query */
        $query = preg_replace('/[^a-zA-Z0-9\-\s]+/', '', $query);

        $scoutQuery = static::baseSearch($query);

        if (env('SCOUT_DRIVER') === 'elastic') {
            $scoutQuery = static::modifyElasticQuery($scoutQuery, $query);
        }

        return $scoutQuery;
    }

    public function scopeSearch(Builder $query, string $searchable, bool $orderByScore): void
    {
        /** @phpstan-ignore-next-line */
        $scoutSearchQuery = static::search($searchable);
        $ids = $scoutSearchQuery->take($this->maxSearchResultsCount)->get()->pluck('id');
        $table = $this->getTable();

        $query->whereIntegerInRaw("{$table}.id", $ids);

        if (count($ids) > 1 && $orderByScore) {
            // TODO: No comments
            $orderByRaw = 'CASE' . PHP_EOL;
            foreach ($ids as $i => $id) {
                $orderByRaw = "{$orderByRaw} WHEN {$table}.id={$id} THEN {$i}" . PHP_EOL;
            }
            $orderByRaw = "{$orderByRaw} END";

            $query->orderByRaw(DB::raw($orderByRaw));
        }
    }

    protected static function modifyElasticQuery(ScoutBuilder $query, string $searchable): ScoutBuilder
    {
        $query->query = '';

        return $query->must(new QueryString("*{$searchable}*", QueryString::OP_AND));
    }
}
