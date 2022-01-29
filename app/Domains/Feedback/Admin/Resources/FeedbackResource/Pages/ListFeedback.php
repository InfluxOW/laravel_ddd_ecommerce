<?php

namespace App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\Feedback\Admin\Resources\FeedbackResource;

final class ListFeedback extends ListRecords
{
    protected static string $resource = FeedbackResource::class;
}
