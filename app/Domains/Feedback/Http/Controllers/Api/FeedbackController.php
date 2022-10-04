<?php

namespace App\Domains\Feedback\Http\Controllers\Api;

use App\Domains\Feedback\Actions\StoreFeedbackAction;
use App\Domains\Feedback\Http\Requests\FeedbackRequest;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Feedback\Models\Settings\FeedbackSettings;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class FeedbackController extends Controller
{
    public function store(FeedbackRequest $request, FeedbackSettings $settings, StoreFeedbackAction $action): JsonResponse
    {
        $this->authorize($this->resourceAbilityMap()[__FUNCTION__], [Feedback::class, $request, $settings->feedback_limit_per_hour]);

        $action->execute($request);

        return $this->respondSuccess();
    }
}
