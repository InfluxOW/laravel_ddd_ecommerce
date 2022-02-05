<?php

namespace App\Domains\Feedback\Http\Controllers\Api\Virtual;

use OpenApi\Annotations as OA;

final class FeedbackController
{
    /**
     * @OA\Post(
     *    path="/feedback",
     *    summary="Feedback",
     *    description="Submit Feedback",
     *    operationId="feedbackStore",
     *    tags={"Feedback"},
     *    @OA\RequestBody(
     *       required=true,
     *       description="User data",
     *       @OA\JsonContent(
     *          required={"text"},
     *          @OA\Property(property="username", type="string", example="John Doe"),
     *          @OA\Property(property="email", type="string", format="email", example="john_doe@mail.com"),
     *          @OA\Property(property="phone", type="string", pattern="/^\+[\d]{11}$/", example="+79999999999"),
     *          @OA\Property(property="text", type="string", example="I don't understand how can I place an order. Can you help me?"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response=204,
     *       description="Feedback has been submitted",
     *       @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *    response=422,
     *    description="Validation Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid."),
     *       @OA\Property(
     *       property="errors",
     *       type="object",
     *          @OA\Property(
     *             property="email",
     *             type="array",
     *             collectionFormat="multi",
     *             @OA\Items(
     *                type="string",
     *                example={"The email must be a valid email address."},
     *             ),
     *          ),
     *       ),
     *    ),
     *    ),
     *    @OA\Response(
     *    response=409,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="You have posted too much feedback. Please, try again in 29 minutes!"),
     *    ),
     *    ),
     * )
     */
    public function store(): void
    {
        //
    }
}
