<?php

namespace App\Components\Queryable\Abstracts;

abstract class Query
{
    public string $query;
    public string $title;

    public function toArray(): array
    {
        return [
            'query' => $this->query,
            'title' => $this->title,
        ];
    }
}
