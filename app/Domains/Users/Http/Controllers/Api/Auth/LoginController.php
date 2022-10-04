<?php

namespace App\Domains\Users\Http\Controllers\Api\Auth;

use App\Domains\Users\Actions\LoginUserAction;
use App\Domains\Users\Http\Requests\LoginRequest;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, LoginUserAction $action): JsonResponse
    {
        $accessToken = $action->execute($request);

        return $this->respondWithCustomData([
            'access_token' => $accessToken->plainTextToken,
        ]);
    }
}
