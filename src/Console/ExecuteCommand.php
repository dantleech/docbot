<?php

namespace Dantleech\Exedoc\Console;

use Dantleech\Exedoc\Model\ArticleFinder;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'execute', description: 'Execute docs')]
final class ExecuteCommand extends Command
{
    public function __construct(private ArticleFinder $finder)
    {

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Path to tutorials'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        if (!is_string($path)) {
            throw new RuntimeException(sprintf(
                'Path is not a string, it is; %s',
                get_debug_type($path)
            ));
        }

        $docs = $this->finder->findInPath($path);

        return 0;
    }
}
