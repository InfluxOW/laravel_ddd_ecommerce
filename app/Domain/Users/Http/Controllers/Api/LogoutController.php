<?php

namespace App\Domain\Users\Http\Controllers\Api;

use App\Interfaces\Http\Controllers\Controller;
use App\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $user->tokens()->delete();

        return response(['message' => 'You are successfully logged out.'], 200);
    }
}
