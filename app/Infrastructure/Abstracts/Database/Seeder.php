<?php

namespace App\Infrastructure\Abstracts\Database;

use App\Application\Classes\ApplicationState;
use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Seeder as BaseSeeder;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;
use Laravel\Octane\Facades\Octane;

abstract class Seeder extends BaseSeeder
{
    public function __invoke(array $parameters = [])
    {
        ApplicationState::$isRunningSeeders = true;

        $result = parent::__invoke($parameters);

        ApplicationState::$isRunningSeeders = false;

        return $result;
    }

    /**
     * @param class-string<Model> $class
     * @param int                 $chunkSize
     *
     * @return int[]
     */
    protected function seedModelByChunks(string $class, int $count, int $chunkSize = 100, int $chunkSliceSize = 20): array
    {
        $classTraits = class_uses($class);
        if (is_array($classTraits) && isset($classTraits[HasFactory::class])) {
            /** @phpstan-ignore-next-line */
            $rows = LazyCollection::make($class::factory()->count($count)->make()->map->getRawAttributes(['id']));

            return $this->insertByChunks((new $class())->getTable(), $rows, $chunkSize, $chunkSliceSize);
        }

        throw new InvalidArgumentException("Model `{$class}` should have `HasFactory` trait.");
    }

    /**
     * @param BelongsToMany $relation
     * @param int[]         $foreignIds
     * @param int[]         $relatedIds
     *
     * @return void
     */
    protected function seedBelongsToManyRelationByChunks(BelongsToMany $relation, array $foreignIds, array $relatedIds, Closure $takeRelatedIds): void
    {
        $rows = [];
        foreach ($foreignIds as $foreignId) {
            $rows[] = $this->getBelongsToManyRows($relation, $foreignId, $takeRelatedIds($relatedIds));
        }

        $this->insertByChunks($relation->getTable(), collect($rows)->flatten(depth: 1));
    }

    /**
     * @param BelongsToMany $relation
     * @param int           $foreignId
     * @param int[]         $relatedIds
     *
     * @return array
     */
    private function getBelongsToManyRows(BelongsToMany $relation, int $foreignId, array $relatedIds): array
    {
        $rows = [];
        foreach ($relatedIds as $relatedId) {
            $rows[] = Factory::addTimestamps([
                $relation->getForeignPivotKeyName() => $foreignId,
                $relation->getRelatedPivotKeyName() => $relatedId,
            ], true);
        }

        return $rows;
    }

    /**
     * @return int[]
     */
    protected function insertByChunks(string $table, Enumerable $rows, int $chunkSize = 100, int $chunkSliceSize = 20): array
    {
        $maxId = DB::table($table)->max('id') ?? 0;

        $rows
            ->chunk($chunkSize)
            ->each(function (Enumerable $chunk) use ($table, $chunkSize, $chunkSliceSize): void {
                $insertsCount = ceil($chunkSize / $chunkSliceSize);

                $inserts = [];
                for ($i = 0; $i < $insertsCount; $i += 1) {
                    $inserts[] = static fn () => DB::table($table)->insert($chunk->slice($i * $chunkSliceSize, $chunkSliceSize)->toArray()); // Unable to use Closures from out of function scope due to serialization issues.
                }

                Octane::concurrently($inserts, 30000);
            });

        return DB::table($table)
            ->where('id', '>', $maxId)
            ->pluck('id')
            ->toArray();
    }
}
