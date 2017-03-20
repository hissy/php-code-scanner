<?php
namespace Hissy\PhpCodeScanner\Analyzer;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoteConnectAnalyzer
 * @package Hissy\PhpCodeScanner\Analyzer
 */
class RemoteConnectAnalyzer extends AbstractAnalyzer implements AnalyzerInterface
{
    /**
     * @var array
     */
    protected $functions = [
        'curl_exec'
    ];

    /**
     * @param \SplFileInfo $fileInfo
     * @param OutputInterface $output
     */
    public function analyze(\SplFileInfo $fileInfo, OutputInterface $output)
    {
        $tokens = $this->getTokens($fileInfo);
        foreach ($this->functions as $function) {
            $positions = $this->findFunction($tokens, $function);
            foreach ($positions as $position) {
                $output->writeln(sprintf('<info>%s found in %s on line %d</info>', $function, $fileInfo->getPathname(), $position));
            }
        }
    }
}