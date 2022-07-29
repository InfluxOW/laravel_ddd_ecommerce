<?php

namespace App\Domains\News\Admin\Resources\ArticleResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\EditRecord;
use App\Domains\News\Admin\Resources\ArticleResource;

final class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;
}
