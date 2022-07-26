<?php

namespace App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;
use App\Domains\Feedback\Admin\Resources\FeedbackResource;

final class EditFeedback extends EditRecord
{
    protected static string $resource = FeedbackResource::class;
}
