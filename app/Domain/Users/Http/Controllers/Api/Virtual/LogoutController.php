<?php

namespace App\Domain\Users\Http\Controllers\Api\Virtual;

class LogoutController
{
    /**
     * @OA\Post(
     *    path="/logout",
     *    summary="Sign Out",
     *    description="Logout",
     *    operationId="authLogout",
     *    tags={"Authentication"},
     *    security={
     *      {"access_token": {}},
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
