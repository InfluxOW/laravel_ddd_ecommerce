<?php

namespace App\Domains\Users\Http\Controllers\Api\Virtual;

use OpenApi\Annotations as OA;

final class RegisterController
{
    /**
     * @OA\Post(
     *    path="/register",
     *    summary="Register",
     *    description="Register a new user",
     *    operationId="authRegister",
     *    tags={"Authentication"},
     *    @OA\RequestBody(
     *       required=true,
     *       description="User data",
     *       @OA\JsonContent(
     *          required={"name", "email", "role", "password", "password_confirmation"},
     *          @OA\Property(property="name", type="string", example="John Doe"),
     *          @OA\Property(property="email", type="string", format="email", example="john_doe@mail.com"),
     *          @OA\Property(property="password", type="string", example="password"),
     *          @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *          @OA\Property(property="g-recaptcha-response", type="string", example="captcha"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=201,
     *       description="User has been registered",
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
     * )
     */
    public function __invoke(): void
    {
        //
    }
}
