<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $user->tokens()->delete();

        return response(['message' => 'You are successfully logged out.'], 200);
    }
}
