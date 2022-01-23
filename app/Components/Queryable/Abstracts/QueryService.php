<?php

namespace App\Components\Queryable\Abstracts;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface QueryService
{
    /**
     * @return Collection<Query>
     */
    public function getAllowed(): Collection;

    /**
     * @param Request $request
     * @return Collection<Query>|Query|null
     */
    public function getApplied(Request $request): Collection|Query|null;
}
