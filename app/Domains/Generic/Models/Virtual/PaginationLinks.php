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
     * @var string
     *
     * @example http://localhost:8085/api/products?page=1
     */
    public $first;

    /**
     * @OA\Property()
     *
     * @var string
     *
     * @example http://localhost:8085/api/products?page=4
     */
    public $last;

    /**
     * @OA\Property()
     *
     * @var string|null
     *
     * @example http://localhost:8085/api/products?page=1
     */
    public $prev;

    /**
     * @OA\Property()
     *
     * @var string|null
     *
     * @example http://localhost:8085/api/products?page=3
     */
    public $next;
}
