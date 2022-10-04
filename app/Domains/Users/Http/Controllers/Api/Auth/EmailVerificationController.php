<?php

namespace App\Domains\Users\Http\Controllers\Api\Auth;

use App\Domains\Users\Actions\VerifyEmailUserAction;
use App\Domains\Users\Http\Requests\EmailVerificationRequest;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class EmailVerificationController extends Controller
{
    public function __invoke(EmailVerificationRequest $request, VerifyEmailUserAction $action): JsonResponse
    {
        $action->execute($request);

        return $this->respondSuccess();
    }
}
