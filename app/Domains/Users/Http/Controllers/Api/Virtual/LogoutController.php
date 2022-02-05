<?php

namespace App\Domains\Users\Http\Controllers\Api\Virtual;

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
     *    @OA\Response(
     *       response=200,
     *       description="Successful logout",
     *       @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="You are successfully logged out."),
     *       ),
     *    ),
     * )
     */
    public function __invoke(): void
    {
        //
    }
}
