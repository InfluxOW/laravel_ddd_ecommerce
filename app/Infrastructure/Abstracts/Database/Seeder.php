<?php

namespace App\Infrastructure\Abstracts\Database;

use App\Application\Classes\ApplicationState;
use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Seeder as BaseSeeder;
use Illuminate\Events\NullDispatcher;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Octane\Facades\Octane;
use Laravel\Scout\ModelObserver;

abstract class Seeder extends BaseSeeder
{
    private Dispatcher $cacheEventDispatcher;

    /**
     * @var class-string<Model>|null
     */
    private ?string $model;

    private function beforeInvoke(): void
    {
        ApplicationState::$isRunningSeeders = true;

        $this->disableSimpleCacheEventsHandling();
        $this->disableDevTools();
        $this->disableScoutObserver();
    }

    public function __invoke(array $parameters = [])
    {
        $this->beforeInvoke();

        $result = parent::__invoke($parameters);

        $this->afterInvoke();

        return $result;
    }

    private function afterInvoke(): void
    {
        ApplicationState::$isRunningSeeders = false;

        $this->enableSimpleCacheEventsHandling();
        $this->enableDevTools();
        $this->enableScoutObserver();
    }

    /**
     * @param class-string<Model> $class
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

    private function disableSimpleCacheEventsHandling(): void
    {
        Cache::simple()->setEventDispatcher(new NullDispatcher($this->cacheEventDispatcher = Cache::getEventDispatcher()));
    }

    private function enableSimpleCacheEventsHandling(): void
    {
        Cache::simple()->setEventDispatcher($this->cacheEventDispatcher);
    }

    private function disableDevTools(): void
    {
        config(['telescope.enabled' => false, 'clockwork.enable' => false]);
    }

    private function enableDevTools(): void
    {
        config(['telescope.enabled' => env('TELESCOPE_ENABLED', true), 'clockwork.enable' => env('CLOCKWORK_ENABLE')]);
    }

    private function disableScoutObserver(): void
    {
        $this->model = $this->guessRelatedModel();

        if (isset($this->model)) {
            ModelObserver::disableSyncingFor($this->model);
        }
    }

    private function enableScoutObserver(): void
    {
        if (isset($this->model)) {
            ModelObserver::enableSyncingFor($this->model);
        }
    }

    /**
     * @return class-string<Model>|null
     */
    private function guessRelatedModel(): ?string
    {
        $classnameParts = Str::of(static::class)->explode('\\');
        $possibleBasename = Str::of((string) $classnameParts->pop())->replace('Seeder', '')->value();
        $classnameParts->pop(2);
        $classnameParts->push('Models');
        $classnameParts->push($possibleBasename);
        /** @var class-string<Model> $possibleModel */
        $possibleModel = $classnameParts->implode('\\');

        return class_exists($possibleModel) ? $possibleModel : null;
    }
}
