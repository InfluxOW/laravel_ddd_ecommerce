<?php

namespace App\Domains\Users\Models\Virtual;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
final class User
{
    /**
     * @OA\Property()
     *
     * @example John Doe
     */
    public string $name;

    /**
     * @OA\Property(format="email")
     *
     * @example john_doe@gmail.com
     */
    public string $email;

    /**
     * @OA\Property(format="phone")
     *
     * @example +12225657785
     */
    public string $phone;

    /**
     * @OA\Property(format="date-time")
     *
     * @example 2022-02-05T04:21:52+00:00
     */
    public string $created_at;
}
