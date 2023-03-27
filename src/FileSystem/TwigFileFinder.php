<?php

declare(strict_types=1);

namespace TomasVotruba\Laravelize\FileSystem;

use Webmozart\Assert\Assert;

final class TwigFileFinder
{
    /**
     * @return string[]
     */
    public function findTwigFilePaths(string $templatesDirectory): array
    {
        /** @var string[] $twigFilePaths */
        $twigFilePaths = glob($templatesDirectory . '/*/*.twig');
        Assert::allString($twigFilePaths);

        // use realpaths
        return array_map(static fn (string $twigFilePath): string => realpath($twigFilePath), $twigFilePaths);
    }
}
