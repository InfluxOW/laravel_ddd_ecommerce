<?php

namespace App\Domain\Users\Http\Controllers\Api;

use App\Domain\Users\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * @OA\Post(
     * path="/logout",
     * summary="Sign Out",
     * description="Logout",
     * operationId="authLogout",
     * tags={"Authentication"},
     * security={
     *   {"access_token": {}},
     * },
     * @OA\Response(
     *    response=200,
     *    description="Successful logout",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="You are successfully logged out."),
     *    )
     * ),
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json(['message' => 'You are successfully logged out.']);
    }
}
