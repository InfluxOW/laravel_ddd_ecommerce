<?php

namespace App\Domains\Users\Http\Controllers\Api;

use App\Domains\Users\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->tokens()->delete();

        return $this->respondWithMessage('You are successfully logged out.');
    }
}
