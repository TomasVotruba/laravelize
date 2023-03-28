<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use TomasVotruba\Laravelize\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->removeUnusedImports();

    $rectorConfig->import(__DIR__ . '/../../../../../config/rector/services.php');

    $rectorConfig->rule(SymfonyRouteAttributesToLaravelRouteFileRector::class);
};
