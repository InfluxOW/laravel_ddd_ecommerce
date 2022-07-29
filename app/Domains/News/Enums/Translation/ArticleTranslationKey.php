<?php

namespace App\Domains\News\Enums\Translation;

enum ArticleTranslationKey: string
{
    case ID = 'id';
    case TITLE = 'title';
    case SLUG = 'slug';
    case DESCRIPTION = 'description';
    case BODY = 'body';
    case IMAGES = 'images';
    case IS_PUBLISHED = 'is_published';
    case PUBLISHED_AT = 'published_at';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
}
