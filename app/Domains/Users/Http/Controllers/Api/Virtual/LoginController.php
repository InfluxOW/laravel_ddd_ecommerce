<?php

namespace App\Domains\Users\Http\Controllers\Api\Virtual;

use OpenApi\Annotations as OA;

final class LoginController
{
    /**
     * @OA\Post(
     *    path="/login",
     *    summary="Sign In",
     *    description="Login by email and password",
     *    operationId="authLogin",
     *    tags={"Authentication"},
     *    @OA\RequestBody(
     *       required=true,
     *       description="User credentials",
     *       @OA\JsonContent(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="string", format="email", example="user@mail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="password"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=200,
     *       description="Successful login",
     *       @OA\JsonContent(
     *          @OA\Property(property="access_token", type="string", example="1|rJpCvUMYDBPJObhURa6d7sLWX6TMhBykTxaE8tfz"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=403,
     *       description="Forbidden",
     *       @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="We sent a confirmation email to john_doe@mail.com. Please, follow the instructions to complete your registration."),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=422,
     *       description="Validation Error",
     *       @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please, try again!"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=500,
     *       description="Login failed",
     *       @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Sorry, something went wrong. Please, try again later!"),
     *       ),
     *    ),
     * )
     */
    public function __invoke(): void
    {
        //
    }
}
