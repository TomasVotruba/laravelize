<?php

declare(strict_types=1);

namespace TomasVotruba\Laravelize;

use Illuminate\Console\OutputStyle;
use Nette\Utils\FileSystem;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use TomasVotruba\Laravelize\Console\Output\ColorConsoleDiffFormatter;
use TomasVotruba\Laravelize\FileSystem\TwigFileFinder;

/**
 * @see \TomasVotruba\Laravelize\Tests\TwigToBladeConverter\TwigToBladeConverterTest
 */
final class TwigToBladeConverter
{
    /**
     * @var array<string, string>
     */
    private const TWIG_TO_BLADE_REPLACE_REGEXES = [
        // layout
        '#{\% extends ("|\')(.*?)\.twig("|\') \%\}#' => '@extends(\'$2\')',
        '#{\% block (.*?) \%}#' => '@section(\'$1\')',
        '#{\% endblock \%}#' => '@endsection',
        '#{\% set (\w+) \%}(.*?){% endset %}#' => '@php $$1 = \'$2\'; @endphp',

        '#{\% include(\s+)?[\(]?(\'|\")(.*?)\.twig(\'|\")[\)]? \%}#' => '@include(\'$3\')',

        // control structures
        '#{% (if|elseif) (?<variable>\w+)\.(?<property>\w+) %}#' => '@$1 ($$2->$3)',
        '#{% if (?<condition>.*?) %}#' => '@if ($1)',
        '#{% for (?<singular>.*?) in (?<plural>.*?) %}#' => '@foreach ($$2 as $$1)',
        '#{% else %}#' => '@else ',
        '#{% endif %}#' => '@endif',
        '#{% endfor %}#' => '@endforeach',
        '#\{\# @var (?<variable>.*?) (?<type>.*?) \#\}#' => '@php /** @var $$1 $2 */ @endphp',
        '#path\((.*?)\)#' => 'route($1)',
        '#\{ (?<key>\w+)\: (?<value>.*?) \}#' => '[\'$1\' => $2]',

        // variables
        '#{{ (?<variable>\w+)\.(?<method>\w+) }}#' => '{{ $$1->$2 }}',
        '#{{ (?<variable>\w+) }}#' => '{{ $$1 }}',
        '#{{ (?<variable>\w+).(?<property>\w+)\|date\((.*?)\) }}#' => '{{ $$1->$2->format($3) }}',
        '#{{ (?<variable>\w+).(?<property>\w+)\|date }}#' => '{{ $$1->$2->format() }}',

        # filters
        '#{{ (?<value>\w+)\|raw }}#' => '{!! $1 !!}',
        '#{{ (?<variable>\w+)\|(?<filter>\w+) }}#' => '{{ $2($$1) }}',

        # comments
        '#\{\# (.*?) \#\}#' => '{{-- $1 --}}',
    ];

    private readonly Differ $differ;

    public function __construct(
        private readonly ColorConsoleDiffFormatter $colorConsoleDiffFormatter,
        private readonly TwigFileFinder $twigFileFinder,
    ) {
        $this->differ = new Differ(new UnifiedDiffOutputBuilder());
    }

    public function run(string $templatesDirectory, OutputStyle $outputStyle): void
    {
        $twigFilePaths = $this->twigFileFinder->findTwigFilePaths($templatesDirectory);

        $foundFilesMessage = sprintf('Found %d *.twig files', count($twigFilePaths));
        $outputStyle->note($foundFilesMessage);

        foreach ($twigFilePaths as $twigFilePath) {
            $twigFileContents = FileSystem::read($twigFilePath);
            $bladeFileContents = $this->convertFile($twigFileContents);

            // nothing to change
            if ($twigFileContents === $bladeFileContents) {
                continue;
            }

            $diff = $this->differ->diff($twigFileContents, $bladeFileContents);
            $colorDiff = $this->colorConsoleDiffFormatter->format($diff);
            $outputStyle->writeln($colorDiff);

            $bladeFilePath = substr($twigFilePath, 0, -5) . '.blade.php';
            FileSystem::write($bladeFilePath, $bladeFileContents);
        }
    }

    public function convertFile(string $twigFileContents): string
    {
        $bladeFileContents = $twigFileContents;

        foreach (self::TWIG_TO_BLADE_REPLACE_REGEXES as $twigRegex => $bladeReplacement) {
            $bladeFileContents = str($bladeFileContents)
                ->replaceMatches($twigRegex, $bladeReplacement)
                ->value();
        }

        return $bladeFileContents;
    }
}
