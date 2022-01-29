<?php

namespace App\Domains\Users\Models\Virtual;

/**
 * @OA\Schema(
 *    @OA\Xml(name="User")
 * )
 */
final class User
{
    /**
     * @OA\Property()
     * @var string
     * @example John Doe
     */
    public $name;

    /**
     * @OA\Property(format="email")
     * @var string
     * @example john_doe@gmail.com
     */
    public $email;

    /**
     * @OA\Property(format="phone")
     * @var string
     * @example +12225657785
     */
    public $phone;

    /**
     * @OA\Property()
     * @var string
     * @example 20 Oct 2020 22:40:18
     */
    public $created_at;
}
