<?php

use App\Domains\Admin\Admin\Components\Actions\Tables\BulkUpdateAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\ExportAction;
use App\Domains\Admin\Admin\Resources\Development\ClockworkLinkResource;
use App\Domains\Admin\Admin\Resources\Development\ElasticvueLinkResource;
use App\Domains\Admin\Admin\Resources\Development\HorizonLinkResource;
use App\Domains\Admin\Admin\Resources\Development\KibanaLinkResource;
use App\Domains\Admin\Admin\Resources\Development\PrequelLinkResource;
use App\Domains\Admin\Admin\Resources\Development\RabbitMQLinkResource;
use App\Domains\Admin\Admin\Resources\Development\SwaggerLinkResource;
use App\Domains\Admin\Admin\Resources\Development\TelescopeLinkResource;
use App\Domains\Admin\Admin\Resources\Development\TotemLinkResource;
use App\Domains\Admin\Enums\Translation\AdminModalTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Generic\Utils\LangUtils;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

return [
    BulkUpdateAction::class => [
        AdminModalTranslationKey::HEADING->name => 'Update Records',
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you want to update these records?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    ExportAction::class => [
        AdminModalTranslationKey::HEADING->name => 'Export Records',
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you want to export these records?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    DeleteAction::class => [
        AdminModalTranslationKey::HEADING->name => [
            static fn (Page $livewire, ?Model $record) => "Delete {$livewire::getResource()::getRecordTitle($record)}",
        ],
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you would like to do this?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    SwaggerLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Swagger',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Swagger',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Swagger',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
    ClockworkLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Clockwork',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Clockwork',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Clockwork',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
    TelescopeLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Telescope',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Telescope',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Telescope',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
    PrequelLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Prequel',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Prequel',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Prequel',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
    TotemLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Totem',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Totem',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Totem',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
    HorizonLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Horizon',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Horizon',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Horizon',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
    RabbitMQLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'RabbitMQ',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'RabbitMQ',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'RabbitMQ',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
    ElasticvueLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Elasticvue',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Elasticvue',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Elasticvue',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
    KibanaLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Kibana',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Kibana',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Kibana',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
];
