<?php

declare(strict_types=1);

namespace TomasVotruba\Laravelize\FileSystem;

use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Webmozart\Assert\Assert;

final class TwigFileFinder
{
    /**
     * @return string[]
     */
    public function findTwigFilePaths(string $templatesDirectory): array
    {
        $twigFinder = Finder::create()
            ->in($templatesDirectory)
            ->name('*.twig');

        $twigFilePaths = iterator_to_array($twigFinder);

        return $this->resolveAbsoluteFilePaths($twigFilePaths);
    }

    /**
     * @param SplFileInfo[] $twigFileInfos
     * @return string[]
     */
    private function resolveAbsoluteFilePaths(array $twigFileInfos): array
    {
        Assert::allIsInstanceOf($twigFileInfos, SplFileInfo::class);

        return array_map(static fn (SplFileInfo $twigFileInfo): string => $twigFileInfo->getRealPath(), $twigFileInfos);
    }
}
