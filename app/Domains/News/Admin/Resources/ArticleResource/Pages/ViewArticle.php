<?php

namespace App\Domains\News\Admin\Resources\ArticleResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ViewRecord;
use App\Domains\News\Admin\Resources\ArticleResource;

final class ViewArticle extends ViewRecord
{
    protected static string $resource = ArticleResource::class;
}
