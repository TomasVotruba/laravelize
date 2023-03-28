<?php

declare(strict_types=1);

namespace TomasVotruba\Laravelize\Tests\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector;

use Nette\Utils\FileSystem;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SymfonyRouteAttributesToLaravelRouteFileRectorTest extends AbstractRectorTestCase
{
    protected function tearDown(): void
    {
        // clear routes
        FileSystem::delete(getcwd() . '/routes/web.php');
    }

    public function test(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/some_controller.php.inc');

        $this->assertFileWasAdded(
            getcwd() . '/routes/web.php',
            FileSystem::read(__DIR__ . '/Expected/expected_dumped_routes.php')
        );
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
