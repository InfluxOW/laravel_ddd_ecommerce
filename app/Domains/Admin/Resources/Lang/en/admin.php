<?php

use App\Domains\Admin\Admin\Components\Actions\Tables\BulkUpdateAction;
use App\Domains\Admin\Admin\Components\Actions\Tables\DeleteAction;
use App\Domains\Admin\Admin\Components\Widgets\CustomersChartWidget;
use App\Domains\Admin\Enums\Translation\AdminModalTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

return [
    CustomersChartWidget::class => [
        AdminWidgetPropertyTranslationKey::HEADING->name => 'Customers Per Month',
    ],
    BulkUpdateAction::class => [
        AdminModalTranslationKey::HEADING->name => 'Update Records',
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you want to update these records?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
    DeleteAction::class => [
        AdminModalTranslationKey::HEADING->name => [
            static fn (Page $livewire, ?Model $record) => "Delete {$livewire::getResource()::getRecordTitle($record)}",
        ],
        AdminModalTranslationKey::SUBHEADING->name => 'Are you sure you would like to do this?',
        AdminModalTranslationKey::BUTTON->name => 'Confirm',
    ],
];
