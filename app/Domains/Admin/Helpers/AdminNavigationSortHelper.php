<?php

namespace App\Domains\Admin\Helpers;

use App\Components\Attributable\Admin\Resources\AttributeResource;
use App\Domains\Admin\Admin\Resources\AdminResource;
use App\Domains\Admin\Admin\Resources\Development\ClockworkLinkResource;
use App\Domains\Admin\Admin\Resources\Development\ElasticvueLinkResource;
use App\Domains\Admin\Admin\Resources\Development\HorizonLinkResource;
use App\Domains\Admin\Admin\Resources\Development\KibanaLinkResource;
use App\Domains\Admin\Admin\Resources\Development\PhpCacheAdminLinkResource;
use App\Domains\Admin\Admin\Resources\Development\PrequelLinkResource;
use App\Domains\Admin\Admin\Resources\Development\RabbitMQLinkResource;
use App\Domains\Admin\Admin\Resources\Development\SwaggerLinkResource;
use App\Domains\Admin\Admin\Resources\Development\TelescopeLinkResource;
use App\Domains\Admin\Admin\Resources\Development\TotemLinkResource;
use App\Domains\Catalog\Admin\Pages\ManageCatalogSettings;
use App\Domains\Catalog\Admin\Resources\ProductCategoryResource;
use App\Domains\Catalog\Admin\Resources\ProductResource;
use App\Domains\Feedback\Admin\Pages\ManageFeedbackSettings;
use App\Domains\Feedback\Admin\Resources\FeedbackResource;
use App\Domains\News\Admin\Resources\ArticleResource;
use App\Domains\Users\Admin\Resources\UserResource;

final class AdminNavigationSortHelper
{
    public const NAVIGATION_SORT_BY_CLASS = [
        ManageCatalogSettings::class,
        ManageFeedbackSettings::class,
        AdminResource::class,
        UserResource::class,
        FeedbackResource::class,
        ArticleResource::class,
        AttributeResource::class,
        ProductCategoryResource::class,
        ProductResource::class,
        SwaggerLinkResource::class,
        ClockworkLinkResource::class,
        TelescopeLinkResource::class,
        PrequelLinkResource::class,
        TotemLinkResource::class,
        HorizonLinkResource::class,
        RabbitMQLinkResource::class,
        ElasticvueLinkResource::class,
        KibanaLinkResource::class,
        PhpCacheAdminLinkResource::class,
    ];
}
