<?php

use App\Domains\Admin\Admin\Abstracts\RelationManager;
use App\Domains\Admin\Admin\Components\Actions\Create\Tables\CreateAction;
use App\Domains\Admin\Admin\Components\Actions\Delete\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Actions\DeleteBulkAction;
use App\Domains\Admin\Admin\Components\Actions\Edit\Tables\EditAction;
use App\Domains\Admin\Admin\Components\Actions\Export\Pages\ExportAction;
use App\Domains\Admin\Admin\Components\Actions\Export\Tables\ExportBulkAction;
use App\Domains\Admin\Admin\Components\Actions\UpdateBulkAction;
use App\Domains\Admin\Admin\Components\Actions\View\Tables\ViewAction;
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
use App\Domains\Admin\Enums\Translation\AdminModalTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Common\Utils\LangUtils;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

$getModelLabel = static fn (Page|RelationManager $livewire): string => ucwords($livewire instanceof Page ? $livewire::getResource()::getModelLabel() : $livewire->getTableModelLabel());
$getRecordLabel = static fn (Page|RelationManager $livewire, Model $record): string => ucwords($livewire instanceof Page ? $livewire::getResource()::getRecordTitle($record) : $livewire->getTableRecordTitle($record));

return [
    AdminResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Admin',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Admins',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Admins',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::COMMON),
    ],
    UpdateBulkAction::class => [
        AdminModalTranslationKey::HEADING->name => 'Update Records',
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you want to update these records?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    ExportBulkAction::class => [
        AdminModalTranslationKey::HEADING->name => 'Export Selected Records',
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you want to export these records?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    DeleteBulkAction::class => [
        AdminModalTranslationKey::HEADING->name => 'Delete Selected Records',
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you want to delete these records?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    CreateAction::class => [
        AdminModalTranslationKey::HEADING->name => [
            static fn (Page|RelationManager $livewire): string => "Create {$getModelLabel($livewire)}",
        ],
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    EditAction::class => [
        AdminModalTranslationKey::HEADING->name => [
            static fn (Page|RelationManager $livewire, Model $record): string => $getRecordLabel($livewire, $record),
        ],
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    ViewAction::class => [
        AdminModalTranslationKey::HEADING->name => [
            static fn (Page|RelationManager $livewire, Model $record): string => $getRecordLabel($livewire, $record),
        ],
    ],
    ExportAction::class => [
        AdminModalTranslationKey::HEADING->name => 'Export Current Record',
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you want to export this record?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    DeleteAction::class => [
        AdminModalTranslationKey::HEADING->name => [
            static fn (Page $livewire, ?Model $record): string => "Delete {$livewire::getResource()::getRecordTitle($record)}",
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
    PhpCacheAdminLinkResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Cache Dashboard',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Cache Dashboard',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Cache Dashboard',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::DEVELOPMENT),
    ],
];
