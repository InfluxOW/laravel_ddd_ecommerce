<?php

namespace App\Components\Queryable\Abstracts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

interface QueryService
{
    /**
     * @return Collection<Query>
     */
    public function getAllowed(): Collection;

    /**
     * @param Request $request
     *
     * @return Collection<Query>|Query|null
     */
    public function getApplied(Request $request): Collection|Query|null;

    public function toResource(Request $request): JsonResource;
}
