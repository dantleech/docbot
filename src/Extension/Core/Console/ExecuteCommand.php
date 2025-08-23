<?php

namespace DTL\Docbot\Extension\Core\Console;

use DTL\Docbot\Article\ArticleFinder;
use DTL\Docbot\Article\ArticleRenderer;
use DTL\Docbot\Article\ArticleWriter;
use DTL\Docbot\Article\Exception\NoPathsProvided;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Environment\Workspace;
use DTL\Docbot\Extension\Core\CoreExtension;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'execute', description: 'Execute docs')]
final class ExecuteCommand extends Command
{
    const ARG_PATH = 'path';
    const OPT_ARTICLE = 'article';


    public function __construct(
        private ArticleFinder $finder,
        private MainBlockExecutor $executor,
        private ArticleRenderer $renderer,
        private Workspace $workspace,
        private ArticleWriter $writer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARG_PATH, InputArgument::OPTIONAL, 'Path to tutorials');
        $this->addOption(self::OPT_ARTICLE, null, InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY, 'Render specify article(s)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        assert($output instanceof ConsoleOutput);

        $path = $input->getArgument(self::ARG_PATH);
        $targets = $input->getOption(self::OPT_ARTICLE);
        if (!is_array($targets)) {
            throw new RuntimeException(sprintf(
                'For some reason we didn\'t get an array of article titles',
            ));
        }
        $targets = array_map(function (mixed $target) {
            assert(is_string($target));
            return $target;
        }, $targets);
        if (!is_string($path) && !is_null($path)) {
            throw new RuntimeException(sprintf(
                'Path is not a string, it is; %s',
                get_debug_type($path)
            ));
        }

        try {
            $articles = $this->finder->find($path);
        } catch (NoPathsProvided $noPaths) {
            throw new NoPathsProvided(sprintf(
                'You must either provide a path as the first argument or configure them at: `%s`',
                CoreExtension::PARAM_PATHS
            ));
        }
        $err = $output->getErrorOutput();

        $articlesToRun = count($targets) ? $articles->only($targets) : $articles;

        if (count($articles->ids()) === 0) {
            $err->writeln(sprintf(
                'No articles found in path(s): "%s"',
                implode('", "', $this->finder->paths($path))
            ));
            return 0;
        }

        $err->writeln(sprintf('Workspace:</> %s', $this->workspace->path()));
        $err->writeln('');

        foreach ($articlesToRun as $article) {
            $this->workspace->clean();
            $this->executor->execute($articles, $article);
        }

        $err->writeln('');
        $err->writeln('Rendering article:');
        $err->writeln('');

        foreach ($articlesToRun as $article) {
            $rendered = $this->renderer->render($article);
            $result = $this->writer->write($rendered);
            $err->writeln(sprintf('Written %d bytes to %s', $result->bytesWritten, $result->path));
        }

        return 0;
    }
}
