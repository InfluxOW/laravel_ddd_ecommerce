<?php

namespace App\Domains\Common\Mixins;

use Closure;
use Illuminate\Database\Eloquent\Relations\Relation;

final class RelationMixin
{
    public function getAlias(): Closure
    {
        return function (string $morphedModelClass): ?string {
            /** @var string|null $alias */
            $alias = array_flip(Relation::$morphMap)[$morphedModelClass] ?? null;

            return $alias;
        };
    }
}
