<?php

namespace App\Domains\Feedback\Policies;

use App\Domains\Feedback\Models\Feedback;
use App\Domains\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class FeedbackPolicy
{
    use HandlesAuthorization;

    public function create(?User $user, Request $request, ?int $limitPerHour): Response
    {
        if (Feedback::canBeCreated($limitPerHour, $request->ip(), $user)) {
            return Response::allow();
        }

        $denyMessage = sprintf('You have posted too much feedback. Please, try again in %s!', Feedback::getTimeStringDecayBeforeNextFeedback($request->ip(), $user));

        return Response::deny($denyMessage);
    }
}
