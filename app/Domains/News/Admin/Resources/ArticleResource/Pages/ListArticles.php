<?php

namespace App\Domains\News\Admin\Resources\ArticleResource\Pages;

use App\Domains\Admin\Admin\Abstracts\Pages\ListRecords;
use App\Domains\News\Admin\Resources\ArticleResource;

final class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;
}
