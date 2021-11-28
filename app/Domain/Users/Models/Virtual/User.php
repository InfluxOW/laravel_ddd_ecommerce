<?php

namespace App\Domain\Users\Models\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="User")
 * )
 */
class User
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
}
