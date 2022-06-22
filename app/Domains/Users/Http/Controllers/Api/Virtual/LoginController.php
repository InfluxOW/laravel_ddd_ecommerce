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
     *          @OA\Property(property="g-recaptcha-response", type="string", example="captcha"),
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
     *       description="Email verification required",
     *       @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="We sent a confirmation email to john_doe@mail.com. Please, follow the instructions to complete your registration."),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=422,
     *       description="Validation Error",
     *       @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="The given data was invalid."),
     *          @OA\Property(
     *          nullable=true,
     *          property="errors",
     *          type="object",
     *             @OA\Property(
     *                property="email",
     *                type="array",
     *                collectionFormat="multi",
     *                @OA\Items(
     *                   type="string",
     *                   example="The email must be a valid email address.",
     *                ),
     *             ),
     *          ),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=500,
     *       description="Login failed",
     *       @OA\JsonContent(
     *          @OA\Property(property="message", type="string", enum={"Sorry, something went wrong. Please, try again later!"}),
     *       ),
     *    ),
     * )
     */
    public function __invoke(): void
    {
        //
    }
}
