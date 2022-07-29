<?php

namespace App\Domains\News\Admin\Resources\ArticleResource\Pages;

use App\Domains\News\Admin\Resources\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
}
