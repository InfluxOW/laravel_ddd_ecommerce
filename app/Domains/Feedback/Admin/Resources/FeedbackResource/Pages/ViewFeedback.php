<?php

namespace App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages;

use App\Domains\Feedback\Admin\Resources\FeedbackResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewFeedback extends ViewRecord
{
    protected static string $resource = FeedbackResource::class;
}
