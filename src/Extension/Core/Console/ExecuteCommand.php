<?php

namespace DTL\Docbot\Extension\Core\Console;

use DTL\Docbot\Article\ArticleFinder;
use DTL\Docbot\Article\MainBlockExecutor;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'execute', description: 'Execute docs')]
final class ExecuteCommand extends Command
{
    public function __construct(private ArticleFinder $finder, private MainBlockExecutor $executor)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'Path to tutorials');
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

        $articles = $this->finder->findInPath($path);

        foreach ($articles as $article) {
            foreach ($article->blocks as $block) {
                $output->writeln('<comment>==> </>' . $block->describe());
                $this->executor->execute($block);
            }
        }

        return 0;
    }
}