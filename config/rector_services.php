<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use TomasVotruba\Laravelize\Rector\NodeFactory\RouteGetCallFactory;
use TomasVotruba\Laravelize\NodeFactory\SignaturePropertyFactory;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();

    $services->set(RouteGetCallFactory::class);
    $services->set(SignaturePropertyFactory::class);
};
