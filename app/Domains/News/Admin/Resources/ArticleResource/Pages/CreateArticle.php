<?php

namespace App\Domains\News\Admin\Resources\ArticleResource\Pages;

use App\Domains\News\Admin\Resources\ArticleResource;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;

final class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getResourceForm(?int $columns = null, bool $isDisabled = false): Form
    {
        return ArticleResource::form(Form::make()->columns($columns))->schema(ArticleResource::getCreationFormSchema());
    }
}
