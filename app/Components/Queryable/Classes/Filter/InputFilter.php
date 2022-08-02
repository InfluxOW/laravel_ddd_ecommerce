<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Queryable\Enums\QueryFilterType;
use JetBrains\PhpStorm\ArrayShape;

final class InputFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::INPUT;

    private string $input;

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string'])]
    public function allowed(): array
    {
        return $this->toArray();
    }

    #[ArrayShape(['query' => 'string', 'title' => 'string', 'type' => 'string', 'input' => 'string'])]
    public function applied(): array
    {
        return array_merge($this->toArray(), [
            'input' => $this->input,
        ]);
    }

    public function apply(string $input): self
    {
        $filter = clone $this;

        $filter->input = $input;

        return $filter;
    }

    public function isset(): bool
    {
        return isset($this->input);
    }
}
