<?php

use App\Domains\Common\Utils\PathUtils;

return [
    'storage' => [
        'default_path' => env('ELASTIC_MIGRATIONS_DEFAULT_PATH', app_path(PathUtils::join(['Domains', 'Common', 'Database', 'Elastic']))),
    ],
    'database' => [
        'table' => env('ELASTIC_MIGRATIONS_TABLE', 'elastic_migrations'),
        'connection' => env('ELASTIC_MIGRATIONS_CONNECTION', env('DB_CONNECTION')),
    ],
    'prefixes' => [
        'index' => env('ELASTIC_MIGRATIONS_INDEX_PREFIX', env('SCOUT_PREFIX', '')),
        'alias' => env('ELASTIC_MIGRATIONS_ALIAS_PREFIX', env('SCOUT_PREFIX', '')),
    ],
];
