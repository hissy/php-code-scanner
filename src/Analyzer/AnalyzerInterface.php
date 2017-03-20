<?php
namespace Hissy\PhpCodeScanner\Analyzer;

use Symfony\Component\Console\Output\OutputInterface;

interface AnalyzerInterface
{
    public function analyze(\SplFileInfo $fileInfo, OutputInterface $output);
}