<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TomasVotruba\Laravelize\FileSystem\TwigFileFinder;
use TomasVotruba\Laravelize\TwigToBladeConverter;

final class TwigToBladeCommand extends Command
{
    /**
     * @api used by parent command
     *
     * @see https://laravel.com/docs/10.x/artisan#defining-input-expectations
     * @var string
     */
    protected $signature = 'twig-to-blade {paths} {--dry-run}';

    public function __construct(
        private readonly TwigToBladeConverter $twigToBladeConverter,
        private readonly TwigFileFinder $twigFileFinder,
    ) {
        parent::__construct();
    }

    /**
     * @api used by parent command, maybe hanlde in the phpstan rule itself
     */
    public function handle(): int
    {
        /** @var string $templatesDirectory */
        $templatesDirectory = $this->argument('paths');

        if (! file_exists($templatesDirectory)) {
            $this->error('The "%s" directory was not found');
            return self::FAILURE;
        }

        $twigFilePaths = $this->twigFileFinder->findTwigFilePaths($templatesDirectory);

        $foundFilesMessage = sprintf('Found %d "*.twig" files', count($twigFilePaths));
        $this->info($foundFilesMessage);

        $this->twigToBladeConverter->run($twigFilePaths, $templatesDirectory, $this->getOutput());

        $this->info('Templates are now converted to Blade!');

        return self::SUCCESS;
    }
}
