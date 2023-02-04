<?php

namespace App\Domains\Common\Models\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class PaginationMeta
{
    /**
     * @OA\Property()
     *
     * @example 1
     */
    public int $current_page;

    /**
     * @OA\Property()
     *
     * @example 50
     */
    public int $last_page;

    /**
     * @OA\Property()
     *
     * @example 20
     */
    public int $per_page;

    /**
     * @OA\Property()
     *
     * @example 1000
     */
    public int $total;

    /**
     * @OA\Property()
     *
     * @example 1
     */
    public ?int $from = null;

    /**
     * @OA\Property()
     *
     * @example 20
     */
    public ?int $to = null;

    /**
     * @OA\Property()
     *
     * @example http://localhost:8085/api/products
     */
    public string $path;

    /**
     * @OA\Property(
     * type="array",
     * collectionFormat="multi",
     *
     * @OA\Items(
     *    type="object",
     *
     *    @OA\Property(property="url", nullable="true", type="string", example="http://localhost:8085/api/products"),
     *    @OA\Property(property="label", type="string", example="1"),
     *    @OA\Property(property="active",  type="boolean", example="false"),
     * ),
     * )
     */
    public object $links;
}
