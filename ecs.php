<?php

declare(strict_types=1);

use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->skip([
        '*/Expected/*',
        '*/Fixture/*',
    ]);

    $ecsConfig->rules([
        LineLengthFixer::class,
    ]);

    $ecsConfig->sets([
         SetList::COMMON,
         SetList::PSR_12,
    ]);
};
