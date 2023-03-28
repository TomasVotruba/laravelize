<?php

declare(strict_types=1);

namespace TomasVotruba\Laravelize\Console\Output;

use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * Inspired by @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/src/Differ/DiffConsoleFormatter.php to be
 * used as standalone class, without need to require whole package by Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class ColorConsoleDiffFormatter
{
    /**
     * @var string
     * @see https://regex101.com/r/ovLMDF/1
     */
    private const PLUS_START_REGEX = '#^(\+.*)#';

    /**
     * @var string
     * @see https://regex101.com/r/xwywpa/1
     */
    private const MINUS_START_REGEX = '#^(\-.*)#';

    /**
     * @var string
     * @see https://regex101.com/r/CMlwa8/1
     */
    private const AT_START_REGEX = '#^(@.*)#';

    /**
     * @var string
     * @see https://regex101.com/r/qduj2O/1
     */
    private const NEWLINES_REGEX = "#\n\r|\n#";

    private readonly string $template;

    public function __construct()
    {
        $this->template = sprintf(
            '<comment>    ---------- begin diff ----------</comment>%s%%s%s<comment>    ----------- end diff -----------</comment>' . PHP_EOL,
            PHP_EOL,
            PHP_EOL
        );
    }

    public function format(string $diff): string
    {
        return $this->formatWithTemplate($diff, $this->template);
    }

    private function formatWithTemplate(string $diff, string $template): string
    {
        $escapedDiff = OutputFormatter::escape(rtrim($diff));

        $escapedDiffLines = str($escapedDiff)
            ->split(self::NEWLINES_REGEX)
            ->toArray();

        // remove description of added + remove; obvious on diffs
        foreach ($escapedDiffLines as $key => $escapedDiffLine) {
            if ($escapedDiffLine === '--- Original') {
                unset($escapedDiffLines[$key]);
            }

            if ($escapedDiffLine === '+++ New') {
                unset($escapedDiffLines[$key]);
            }
        }

        $coloredLines = array_map(function (string $difFLine): string {
            $difFLine = $this->colorizeLine($difFLine);
            if ($difFLine === ' ') {
                return '';
            }

            return $difFLine;
        }, $escapedDiffLines);

        return sprintf($template, implode(PHP_EOL, $coloredLines));
    }

    private function colorizeLine(string $contents): string
    {
        return str($contents)
            ->replace(self::PLUS_START_REGEX, '<fg=green>$1</fg=green>')
            ->replace(self::MINUS_START_REGEX, '<fg=red>$1</fg=red>')
            ->replace(self::AT_START_REGEX, '<fg=cyan>$1</fg=cyan>')
            ->value();
    }
}
