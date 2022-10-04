<?php

namespace App\Domains\Users\Http\Controllers\Api\Auth;

use App\Domains\Users\Actions\LogoutUserAction;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController extends Controller
{
    public function __invoke(Request $request, LogoutUserAction $action): JsonResponse
    {
        $action->execute($request);

        return $this->respondSuccess();
    }
}
