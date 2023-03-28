<?php

declare(strict_types=1);

namespace TomasVotruba\Laravelize\Tests\TwigToBladeConverter;

use Iterator;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use TomasVotruba\Laravelize\Tests\AbstractTestCase;
use TomasVotruba\Laravelize\TwigToBladeConverter;

final class TwigToBladeConverterTest extends AbstractTestCase
{
    private TwigToBladeConverter $twigToBladeConverter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->twigToBladeConverter = $this->make(TwigToBladeConverter::class);
    }

    #[DataProvider('provideData')]
    public function test(string $fixtureFilePath): void
    {
        $fixtureFileContents = FileSystem::read($fixtureFilePath);

        [$inputTwigContents, $expectedBladeContents] = $this->split($fixtureFileContents);
        $convertedBladeContents = $this->twigToBladeConverter->convertFile($inputTwigContents);

        // update tests
        if (getenv('UT')) {
            FileSystem::write($fixtureFilePath, rtrim($inputTwigContents) . "\n-----\n" . $convertedBladeContents);
            $expectedBladeContents = $convertedBladeContents;
        }

        $this->assertSame($expectedBladeContents, $convertedBladeContents);
    }

    public static function provideData(): Iterator
    {
        /** @var string[] $fixtureFilesPaths */
        $fixtureFilesPaths = glob(__DIR__ . '/Fixture/*.twig.inc');
        foreach ($fixtureFilesPaths as $fixtureFilePath) {
            yield [$fixtureFilePath];
        }
    }

    /**
     * @return array{string, string}
     */
    private function split(string $fileContents): array
    {
        $parts = str($fileContents)
            ->split('#^\-\-\-\-\-\n#m');
        return [$parts[0], $parts[1]];
    }
}
