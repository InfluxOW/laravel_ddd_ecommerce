<?php

namespace App\Domains\Generic\Traits\Models;

use Closure;
use Elastic\ScoutDriverPlus\Builders\BoolQueryBuilder;
use Elastic\ScoutDriverPlus\Searchable as BaseSearchable;
use Elastic\ScoutDriverPlus\Support\Query;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait Searchable
{
    use BaseSearchable {
        search as baseSearch;
    }

    protected static int $maxSearchResultsCount = 100;

    public static function search(string $query = '', ?Closure $callback = null): Collection
    {
        /** @var string $query */
        $query = preg_replace('/[^a-zA-Z0-9\-\s]+/', '', $query);

        if (config('scout.driver') === 'elastic') {
            return static::elasticSearch($query);
        }

        return static::baseSearch($query)->take(static::$maxSearchResultsCount)->get();
    }

    public function scopeSearch(Builder $query, string $searchable, bool $orderByScore): void
    {
        /** @phpstan-ignore-next-line */
        $ids = static::search($searchable)->pluck('id');
        $table = $this->getTable();

        $query->whereIntegerInRaw("{$table}.id", $ids);

        if ($orderByScore && count($ids) > 1) {
            // TODO: No comments
            $orderByRaw = 'CASE' . PHP_EOL;
            foreach ($ids as $i => $id) {
                $orderByRaw = "{$orderByRaw} WHEN {$table}.id={$id} THEN {$i}" . PHP_EOL;
            }
            $orderByRaw = "{$orderByRaw} END";

            $query->orderByRaw(DB::raw($orderByRaw));
        }
    }

    protected static function elasticSearch(string $searchable): Collection
    {
        $query = Query::bool();

        self::shouldFuzzyMatch($query, $searchable);
        self::shouldWildcardMatch($query, $searchable);
        self::shouldExactMatch($query, $searchable);

        $query->minimumShouldMatch(1);

        return static::searchQuery($query)->size(static::$maxSearchResultsCount)->execute()->models();
    }

    private static function shouldFuzzyMatch(BoolQueryBuilder $query, string $searchable): void
    {
        $query->should(
            Query::multiMatch()
                ->query($searchable)
                ->fields(['*'])
                ->fuzziness((string) static::getFuzziness($searchable))
                ->operator('AND')
                ->boost(2.00)
        );
    }

    private static function shouldWildcardMatch(BoolQueryBuilder $query, string $searchable): void
    {
        $query->should([
            'query_string' => [
                'query' => "*{$searchable}*",
                'boost' => 5.0,
                'default_operator' => 'AND',
                'analyze_wildcard' => true,
                'allow_leading_wildcard' => true,
            ],
        ]);
    }

    private static function shouldExactMatch(BoolQueryBuilder $query, string $searchable): void
    {
        $query->should([
            'query_string' => [
                'query' => $searchable,
                'boost' => 10.0,
                'default_operator' => 'AND',
                'analyze_wildcard' => false,
                'allow_leading_wildcard' => false,
            ],
        ]);
    }

    protected static function getFuzziness(string $searchable): int
    {
        $strlen = strlen($searchable);

        return match (true) {
            $strlen > 7 => 2,
            $strlen > 4 => 1,
            default => 0,
        };
    }
}
