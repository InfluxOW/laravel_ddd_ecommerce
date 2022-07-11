<?php

namespace App\Domains\Admin\Admin\Traits;

use App\Domains\Generic\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait AppliesSearchToTableQuery
{
    protected function applySearchToTableQuery(Builder $query): Builder
    {
        /** @var Model $model */
        $model = $this->getModel();
        $traits = class_uses($model);
        $searchQuery = $this->getTableSearchQuery();

        if (is_array($traits) && isset($traits[Searchable::class]) && strlen($searchQuery) > 2) {
            /** @phpstan-ignore-next-line */
            return $query->search($searchQuery, orderByScore: false);
        }

        return parent::applySearchToTableQuery($query);
    }
}
