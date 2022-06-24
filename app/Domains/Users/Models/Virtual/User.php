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
     * @var string
     *
     * @example John Doe
     */
    public $name;

    /**
     * @OA\Property(format="email")
     *
     * @var string
     *
     * @example john_doe@gmail.com
     */
    public $email;

    /**
     * @OA\Property(format="phone")
     *
     * @var string
     *
     * @example +12225657785
     */
    public $phone;

    /**
     * @OA\Property(format="date-time")
     *
     * @var string
     *
     * @example 2022-02-05T04:21:52+00:00
     */
    public $created_at;
}
