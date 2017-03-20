<?php
namespace Hissy\PhpCodeScanner\Analyzer;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EvalAnalyzer
 * @package Hissy\PhpCodeScanner\Analyzer
 */
class EvalAnalyzer extends AbstractAnalyzer implements AnalyzerInterface
{
    /**
     * @var array
     */
    protected $functions = [
        'exec'
    ];

    /**
     * @param \SplFileInfo $fileInfo
     * @param OutputInterface $output
     */
    public function analyze(\SplFileInfo $fileInfo, OutputInterface $output)
    {
        $tokens = $this->getTokens($fileInfo);
        foreach ($tokens as $token) {
            if (is_array($token) && $token[0] == T_EVAL) {
                $output->writeln(sprintf('<error>Eval found in %s on line %d</error>', $fileInfo->getPathname(), $token[2]));
            }
        }
        foreach ($this->functions as $function) {
            $positions = $this->findFunction($tokens, $function);
            foreach ($positions as $position) {
                $output->writeln(sprintf('<error>%s found in %s on line %d</error>', $function, $fileInfo->getPathname(), $position));
            }
        }
    }
}