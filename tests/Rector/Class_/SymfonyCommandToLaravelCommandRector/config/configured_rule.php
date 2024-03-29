<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use TomasVotruba\Laravelize\Rector\Class_\SymfonyCommandToLaravelCommandRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../config/rector/services.php');

    $rectorConfig->removeUnusedImports();

    $rectorConfig->rule(SymfonyCommandToLaravelCommandRector::class);
};
