<?php

namespace App\Domains\Users\Http\Controllers\Api\Virtual;

use OpenApi\Annotations as OA;

final class EmailVerificationController
{
    /**
     * @OA\Post(
     *    path="/user/email/verify",
     *    summary="Verify Email",
     *    description="Confirm registration by verifying email",
     *    operationId="veryfyEmail",
     *    tags={"Authentication"},
     *    @OA\RequestBody(
     *       required=true,
     *       description="Email verification data",
     *       @OA\JsonContent(
     *          required={"email", "token"},
     *          @OA\Property(property="email", type="string", format="email", example="john_doe@mail.com"),
     *          @OA\Property(property="token", type="string", example="XC6NIC"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=204,
     *       description="Email verified",
     *       @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="Email verification failed",
     *       @OA\JsonContent(),
     *    ),
     * )
     */
    public function __invoke(): void
    {
        //
    }
}
