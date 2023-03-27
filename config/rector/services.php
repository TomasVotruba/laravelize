<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use TomasVotruba\Laravelize\NodeFactory\SignaturePropertyFactory;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(\TomasVotruba\Laravelize\NodeFactory\RouteGetCallFactory::class);
    $services->set(SignaturePropertyFactory::class);
};
