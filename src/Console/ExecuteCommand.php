<?php

namespace Dantleech\Exedoc\Console;

use Dantleech\Exedoc\Model\ArticleFinder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'execute', description: 'Execute docs')]
class ExecuteCommand extends Command
{
    public function __construct(ArticleFinder $finder)
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
        $docs = $this->fin
        return 0;
    }
}
