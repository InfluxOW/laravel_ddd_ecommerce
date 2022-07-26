<?php

use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\Actions\ExportActionTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\AdminActionTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\AdminDatasetTranslationKey;
use App\Domains\Admin\Enums\Translation\Components\Cards\AdminTimestampsCardTranslationKey;
use App\Domains\Admin\Enums\Translation\ExportFormat;

return [
    AdminNavigationGroupTranslationKey::class => [
        AdminNavigationGroupTranslationKey::GENERIC->name => 'Generic',
        AdminNavigationGroupTranslationKey::CATALOG->name => 'Catalog',
        AdminNavigationGroupTranslationKey::SETTINGS->name => 'Settings',
        AdminNavigationGroupTranslationKey::FEEDBACK->name => 'Feedback',
        AdminNavigationGroupTranslationKey::DEVELOPMENT->name => 'Development',
    ],
    AdminTimestampsCardTranslationKey::class => [
        AdminTimestampsCardTranslationKey::UPDATED_AT->name => 'Last Modified At',
        AdminTimestampsCardTranslationKey::CREATED_AT->name => 'Created At',
    ],
    AdminActionTranslationKey::class => [
        AdminActionTranslationKey::VIEW->name => 'View',
        AdminActionTranslationKey::DELETE->name => 'Delete',
        AdminActionTranslationKey::UPDATE->name => 'Update',
        AdminActionTranslationKey::EXPORT->name => 'Export',
        AdminActionTranslationKey::BULK_EXPORT->name => 'Export Selected',
    ],
    AdminDatasetTranslationKey::class => [
        AdminDatasetTranslationKey::CUSTOMERS->name => 'Customers',
    ],
    ExportFormat::class => [
        ExportFormat::CSV->name => 'CSV',
        ExportFormat::HTML->name => 'HTML',
        ExportFormat::XLSX->name => 'XLSX',
    ],
    ExportActionTranslationKey::class => [
        ExportActionTranslationKey::FORMAT->name => 'Format',
    ],
];
