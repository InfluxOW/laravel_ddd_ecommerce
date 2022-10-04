<?php

namespace App\Domains\Feedback\Actions;

use App\Domains\Feedback\Http\Requests\FeedbackRequest;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Users\Models\User;

final class StoreFeedbackAction
{
    public function execute(FeedbackRequest $request): void
    {
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
    }
}
