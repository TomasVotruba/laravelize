<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/services.php');

    $rectorConfig->rules([
        \TomasVotruba\Laravelize\Rector\Class_\SymfonyCommandToLaravelCommandRector::class,
        \TomasVotruba\Laravelize\Rector\Class_\SymfonyControllerToLaravelControllerRector::class,
        \TomasVotruba\Laravelize\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector::class,
    ]);
};
