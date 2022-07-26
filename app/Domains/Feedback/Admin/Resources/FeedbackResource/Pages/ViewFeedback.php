<?php

namespace App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\Feedback\Admin\Resources\FeedbackResource;

final class ViewFeedback extends ViewRecord
{
    protected static string $resource = FeedbackResource::class;
}
