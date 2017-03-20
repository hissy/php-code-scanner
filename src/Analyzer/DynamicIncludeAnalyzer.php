<?php
namespace Hissy\PhpCodeScanner\Analyzer;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DynamicIncludeAnalyzer
 * @package Hissy\PhpCodeScanner\Analyzer
 */
class DynamicIncludeAnalyzer extends AbstractAnalyzer implements AnalyzerInterface
{

    /**
     * @param \SplFileInfo $fileInfo
     * @param OutputInterface $output
     */
    public function analyze(\SplFileInfo $fileInfo, OutputInterface $output)
    {
        $tokens = $this->getTokens($fileInfo);

        foreach ($tokens as $index => $token) {
            if (is_array($token) && in_array($token[0], [T_REQUIRE, T_REQUIRE_ONCE, T_INCLUDE, T_INCLUDE_ONCE])) {
                if ($this->catchVariable($tokens, $index)) {
                    $output->writeln(sprintf(
                        '<info>Dynamic include in %s on line %d</info>',
                        $fileInfo->getPathname(),
                        $token[2]
                    ));
                }
            }
        }
    }

}