<?php

namespace App\Domains\Generic\Models\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class PaginationMeta
{
    /**
     * @OA\Property()
     * @var int
     * @example 1
     */
    public $current_page;

    /**
     * @OA\Property()
     * @var int
     * @example 50
     */
    public $last_page;

    /**
     * @OA\Property()
     * @var int
     * @example 20
     */
    public $per_page;

    /**
     * @OA\Property()
     * @var int
     * @example 1000
     */
    public $total;

    /**
     * @OA\Property()
     * @var int|null
     * @example 1
     */
    public $from;

    /**
     * @OA\Property()
     * @var int|null
     * @example 20
     */
    public $to;

    /**
     * @OA\Property()
     * @var string
     * @example http://localhost:8085/api/products
     */
    public $path;

    /**
     * @OA\Property(
     * type="array",
     * collectionFormat="multi",
     * @OA\Items(
     *    type="object",
     *    @OA\Property(property="url", nullable="true", type="string", example="http://localhost:8085/api/products"),
     *    @OA\Property(property="label", type="string", example="1"),
     *    @OA\Property(property="active",  type="boolean", example="false"),
     * ),
     * )
     * @var object
     */
    public $links;
}
