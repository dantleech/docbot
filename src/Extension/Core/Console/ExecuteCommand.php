<?php

namespace DTL\Docbot\Extension\Core\Console;

use DTL\Docbot\Article\ArticleFinder;
use DTL\Docbot\Article\ArticleRenderer;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Environment\Workspace;
use DTL\Docbot\Extension\Core\CoreExtension;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'execute', description: 'Execute docs')]
final class ExecuteCommand extends Command
{
    public function __construct(
        private ArticleFinder $finder,
        private MainBlockExecutor $executor,
        private ArticleRenderer $renderer,
        private Workspace $workspace,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'Path to tutorials');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        assert($output instanceof ConsoleOutput);

        $path = $input->getArgument('path');
        if (!is_string($path)) {
            throw new RuntimeException(sprintf(
                'Path is not a string, it is; %s',
                get_debug_type($path)
            ));
        }

        $articles = $this->finder->findInPath($path);
        $err = $output->getErrorOutput();

        $err->writeln(sprintf('Docbot %s by Daniel Leech', CoreExtension::VERSION));
        $err->writeln(sprintf('Workspace:</> %s', $this->workspace->path()));
        $err->writeln('');
        $this->workspace->clean();

        foreach ($articles as $article) {
            $err->writeln(sprintf('<info>%s</>', $article->title));
            $err->writeln(sprintf('<info>%s</>', str_repeat('=', mb_strlen($article->title))));

            $err->writeln('');
            $err->writeln('Executing article:');
            $err->writeln('');

            foreach ($article->blocks as $block) {
                $err->writeln('<comment>=> </>' . $block->describe());
                $this->executor->execute($block);
            }

            $err->writeln('');
            $err->writeln('Rendering article:');
            $err->writeln('');

            $output->writeln($this->renderer->render($article)->contents);
        }

        return 0;
    }
}
