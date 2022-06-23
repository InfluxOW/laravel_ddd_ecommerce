<?php

use Illuminate\Support\Str;

$dbUrl = (env('DATABASE_URL') === null) ? null : parse_url(env('DATABASE_URL'));
$dbHost = isset($dbUrl) ? $dbUrl['host'] : env('DB_HOST', '127.0.0.1');
$dbPort = isset($dbUrl) ? $dbUrl['port'] : env('DB_PORT', '5432');
$dbDatabase = isset($dbUrl) ? ltrim($dbUrl['path'], '/') : env('DB_DATABASE', 'forge');
$dbUsername = isset($dbUrl) ? $dbUrl['user'] : env('DB_USERNAME', 'forge');
$dbPassword = isset($dbUrl) ? $dbUrl['pass'] : env('DB_PASSWORD', '');

$redisUrl = (env('REDIS_URL') === null) ? null : parse_url(env('REDIS_URL'));
$redisHost = isset($redisUrl) ? $redisUrl['host'] : env('REDIS_HOST', '127.0.0.1');
$redisPassword = isset($redisUrl) ? $redisUrl['pass'] : env('REDIS_PASSWORD');
$redisPort = isset($redisUrl) ? $redisUrl['port'] : env('REDIS_PORT', '6379');

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => $dbHost,
            'port' => $dbPort,
            'database' => $dbDatabase,
            'username' => $dbUsername,
            'password' => $dbPassword,
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => $dbHost,
            'port' => $dbPort,
            'database' => $dbDatabase,
            'username' => $dbUsername,
            'password' => $dbPassword,
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
            'schema' => 'public',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => $redisHost,
            'password' => $redisPassword,
            'port' => $redisPort,
            'database' => env('REDIS_DB', '0'),
            'options' => [
                'serializer' => Redis::SERIALIZER_IGBINARY,
                'compression' => Redis::COMPRESSION_LZ4,
                'compression_level' => 12, // Max for LZ4
            ],
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => $redisHost,
            'password' => $redisPassword,
            'port' => $redisPort,
            'database' => env('REDIS_CACHE_DB', '1'),
            'options' => [
                'serializer' => Redis::SERIALIZER_IGBINARY,
                'compression' => Redis::COMPRESSION_LZ4,
                'compression_level' => 12, // Max for LZ4
            ],
        ],

        'queue' => [
            'url' => env('REDIS_URL'),
            'host' => $redisHost,
            'password' => $redisPassword,
            'port' => $redisPort,
            'database' => env('REDIS_QUEUE_DB', '2'),
        ],

    ],

];
