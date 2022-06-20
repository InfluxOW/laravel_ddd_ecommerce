<?php

$dbConnection = config('database.default');
$dbHost = config("database.connections.{$dbConnection}.host");
$dbPort = config("database.connections.{$dbConnection}.port");
$dbDatabase = config("database.connections.{$dbConnection}.database");
$dbUsername = config("database.connections.{$dbConnection}.username");
$dbPassword = config("database.connections.{$dbConnection}.password");

return [

    /*
    |--------------------------------------------------------------------------
    | Prequel Master Switch : boolean
    |--------------------------------------------------------------------------
    |
    | Manually disable/enable Prequel, if in production Prequel will always be
    | disabled. Reason being that nobody should ever be able to directly look
    | inside your database besides you or your dev team (obviously).
    |
    */

    'enabled' => env('PREQUEL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Prequel Locale : string
    |--------------------------------------------------------------------------
    |
    | Choose what language Prequel should display in.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Prequel Path
    |--------------------------------------------------------------------------
    |
    | The path where Prequel will be residing. Note that this does not affect
    | Prequel API routes.
    |
    */

    'path' => 'prequel',

    /*
    |--------------------------------------------------------------------------
    | Prequel base url
    |--------------------------------------------------------------------------
    |
    | When present, this URL will be used instead of the default url.
    | This should be a complete url excluding tailing slash.
    | Example: 'https://protoqol.nl'
    |
    */
    'baseUrl' => null,

    /*
    |--------------------------------------------------------------------------
    | Prequel Database Configuration : array
    |--------------------------------------------------------------------------
    |
    | This enables you to fully configure your database connection for Prequel.
    |
    */

    'database' => [
        'connection' => $dbConnection,
        'host' => $dbHost,
        'port' => $dbPort,
        'database' => $dbDatabase,
        'username' => $dbUsername,
        'password' => $dbPassword,
        'socket' => env('DB_SOCKET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Prequel ignored databases and tables : array
    |--------------------------------------------------------------------------
    | Databases and tables that will be ignored during database discovery.
    |
    | Using 'mysql' => ['foo']  ignores only the mysql.foo table.
    | Using 'mysql' => ['*'] ignores the entire mysql database.
    |
    */

    'ignored' => [
        '#mysql50#lost+found' => ['*'],
        'postgres' => ['*'],

        // -- Frequently ignored tables --
        // 'information_schema'  => ['*'],
        // 'sys'                 => ['*'],
        // 'performance_schema'  => ['*'],
        // 'mysql'               => ['*'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Prequel pagination per page : integer
    |--------------------------------------------------------------------------
    |
    | When Prequel retrieves paginated information, this is the number of
    | records that will be in each page.
    |
    */

    'pagination' => 20,

    /*
    |--------------------------------------------------------------------------
    | Prequel middleware : array
    |--------------------------------------------------------------------------
    |
    | Define custom middleware for Prequel to use.
    |
    | Ex. 'web', Protoqol\Prequel\Http\Middleware\Authorised::class
    |
    */

    'middleware' => [
        'web',
        'auth:admin',
        Protoqol\Prequel\Http\Middleware\Authorised::class,
    ],
];
