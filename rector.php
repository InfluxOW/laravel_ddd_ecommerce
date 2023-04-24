<?php

declare(strict_types=1);

use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();

    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        LaravelSetList::LARAVEL_100,
    ]);

    $rectorConfig->skip([
        IssetOnPropertyObjectToPropertyExistsRector::class,
        CountOnNullRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        ArgumentAdderRector::class,
        CountArrayToEmptyArrayComparisonRector::class,
        InlineArrayReturnAssignRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
        NullToStrictStringFuncCallArgRector::class,
        RemoveJustVariableAssignRector::class,
        CallableThisArrayToAnonymousFunctionRector::class,
        ExplicitBoolCompareRector::class,
    ]);

    $rectorConfig->cacheClass(FileCacheStorage::class);
    $rectorConfig->cacheDirectory(__DIR__ . '/storage/rector/cache');

    $rectorConfig->parallel(seconds: 240, jobSize: 5);
};
