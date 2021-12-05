<?php

namespace App\Domain\Generic\Query\Interfaces;

use App\Domain\Generic\Query\Abstracts\Query;
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
