<?php

namespace App\Domains\Feedback\Admin\Resources\FeedbackResource\Pages;

use App\Domains\Feedback\Admin\Resources\FeedbackResource;
use Filament\Resources\Pages\EditRecord;

class EditFeedback extends EditRecord
{
    protected static string $resource = FeedbackResource::class;
}
