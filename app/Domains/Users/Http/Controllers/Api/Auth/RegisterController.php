<?php

namespace App\Domains\Users\Http\Controllers\Api\Auth;

use App\Domains\Users\Actions\RegisterUserAction;
use App\Domains\Users\Http\Requests\RegisterRequest;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        $user = $action->execute($request);

        return $this->respondWithMessage("We sent a confirmation email to {$user->email}. Please, follow the instructions to complete your registration.", Response::HTTP_CREATED);
    }
}
