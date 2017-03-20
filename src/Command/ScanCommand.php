<?php
namespace Hissy\PhpCodeScanner\Command;

use Hissy\PhpCodeScanner\Analyzer\AbstractAnalyzer;
use Hissy\PhpCodeScanner\Analyzer\AnalyzerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class ScanCommand
 * @package Hissy\PhpCodeScanner\Command
 */
class ScanCommand extends Command
{
    /**
     * @var array $analysers
     */
    protected $analysers = [];

    /**
     * @param AbstractAnalyzer $analyser
     */
    public function addAnalyser(AbstractAnalyzer $analyser)
    {
        $this->analysers[] = $analyser;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('php-code-scanner')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to scan')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()->ignoreUnreadableDirs()->in($input->getArgument('path'))->name('*.php');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            if ($output->isVeryVerbose()) {
                $output->writeln($file->getPathname());
            }
            /** @var AnalyzerInterface $analyser */
            foreach ($this->analysers as $analyser) {
                $analyser->analyze($file, $output);
            }
        }
    }

}