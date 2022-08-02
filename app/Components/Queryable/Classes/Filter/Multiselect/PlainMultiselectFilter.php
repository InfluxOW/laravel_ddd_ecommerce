<?php

namespace App\Components\Queryable\Classes\Filter\Multiselect;

use App\Components\Queryable\Classes\Filter\Filter;
use App\Components\Queryable\Enums\QueryFilterType;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use UnitEnum;

final class PlainMultiselectFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::PLAIN_MULTISELECT;

    private Collection|EloquentCollection $selected;

    /**
     * @param Collection<string>|EloquentCollection<string> $allowed
     */
    public function __construct(
        UnitEnum $filter,
        public Collection|EloquentCollection $allowed
    ) {
        parent::__construct($filter);
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'allowed' => 'array'])]
    public function allowed(): array
    {
        return array_merge($this->toArray(), [
            'allowed' => $this->allowed->toArray(),
        ]);
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'selected' => 'array'])]
    public function applied(): array
    {
        return array_merge($this->toArray(), [
            'selected' => $this->selected->toArray(),
        ]);
    }

    public function apply(string ...$values): self
    {
        $filter = clone $this;

        $selected = $filter->allowed->filter(fn (string $value): bool => in_array($value, $values, true));

        $filter->selected = $selected->values();

        return $filter;
    }

    public function isset(): bool
    {
        return $this->selected->isNotEmpty();
    }
}
