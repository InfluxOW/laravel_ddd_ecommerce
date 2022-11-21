<?php

namespace App\Domains\Generic\Models\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class PaginationLinks
{
    /**
     * @OA\Property()
     *
     * @example http://localhost:8085/api/products?page=1
     */
    public string $first;

    /**
     * @OA\Property()
     *
     * @example http://localhost:8085/api/products?page=4
     */
    public string $last;

    /**
     * @OA\Property()
     *
     * @example http://localhost:8085/api/products?page=1
     */
    public ?string $prev = null;

    /**
     * @OA\Property()
     *
     * @example http://localhost:8085/api/products?page=3
     */
    public ?string $next = null;
}
