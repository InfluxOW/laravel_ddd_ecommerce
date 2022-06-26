<?php

namespace App\Infrastructure\Abstracts\Database;

use App\Domains\Generic\Traits\Models\HasSimpleCache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TModel of Model
 *
 * @method $this trashed()
 */
abstract class Factory extends BaseFactory
{
    use HasSimpleCache;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection);

        $this->initializeHasSimpleCache();

        $this->setUp();
    }

    public static function addTimestamps(array $data, bool $asString = false): array
    {
        $date = Carbon::now();
        if ($asString) {
            $date = $date->toString();
        }

        return array_merge([
            'created_at' => $date,
            'updated_at' => $date,
        ], $data);
    }

    protected function setUp(): void
    {
        //
    }

    protected function makeInstance(?Model $parent)
    {
        $model = parent::makeInstance($parent);

        $this->setRelations($model);

        return $model;
    }

    private function setRelations(Model $model): void
    {
        /** @var BelongsToRelationship $belongsToRelation */
        foreach ($this->for as $belongsToRelation) {
            [$factory, $relationship] = (fn (): array => [$this->factory, $this->relationship])->call($belongsToRelation);

            if ($factory instanceof Model) {
                $model->setRelation($relationship, $factory);
            }
        }
    }
}
