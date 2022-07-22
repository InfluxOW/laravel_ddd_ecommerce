<?php

namespace App\Domains\Feedback\Http\Controllers\Api;

use App\Domains\Feedback\Http\Requests\FeedbackRequest;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Feedback\Models\Settings\FeedbackSettings;
use App\Domains\Users\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class FeedbackController extends Controller
{
    public function store(FeedbackRequest $request, FeedbackSettings $settings): JsonResponse
    {
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Feedback::class, $request, $settings->feedback_limit_per_hour]);

        /** @var ?User $user */
        $user = $request->user();

        $feedback = Feedback::query()->make($request->validated());
        $feedback->user()->associate($user);
        if (isset($user)) {
            $feedback->username = $user->name;
            $feedback->email = $user->email;
            $feedback->phone = $user->phone;
        }
        $feedback->is_reviewed = false;
        $feedback->ip = $request->getIp();
        $feedback->save();

        return $this->respondSuccess();
    }
}
