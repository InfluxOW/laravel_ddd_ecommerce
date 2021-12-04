<?php

namespace App\Domain\Products\Models\Generic\Filters\Resources\Multiselect;

class MultiselectFilterNestedValuesAttribute
{
    public function __construct(public string $title, public string $slug)
    {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }
}
