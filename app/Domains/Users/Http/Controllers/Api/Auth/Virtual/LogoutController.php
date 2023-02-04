<?php

namespace App\Domains\Users\Http\Controllers\Api\Auth\Virtual;

use OpenApi\Annotations as OA;

final class LogoutController
{
    /**
     * @OA\Post(
     *    path="/logout",
     *    summary="Sign Out",
     *    description="Logout",
     *    operationId="authLogout",
     *    tags={"Authentication"},
     *    security={
     *      {"sanctum": {}},
     *    },
     *
     *    @OA\Response(
     *       response=204,
     *       description="Successful logout",
     *
     *       @OA\JsonContent(),
     *    ),
     * )
     */
    public function __invoke(): void
    {
        //
    }
}
