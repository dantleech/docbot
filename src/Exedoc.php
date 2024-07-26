<?php

namespace Dantleech\Exedoc;

use Dantleech\Exedoc\Extension\Core\Block\CreateFileExecutor;
use Dantleech\Exedoc\Console\ExecuteCommand;
use Dantleech\Exedoc\Extension\Core\Block\ShellBlockExecutor;
use Dantleech\Exedoc\Extension\Core\Block\TextBlockExecutor;
use Dantleech\Exedoc\Model\ArticleFinder;
use Dantleech\Exedoc\Model\MainBlockExecutor;
use Dantleech\Exedoc\Model\ProjectFilesystem;
use RuntimeException;
use Symfony\Component\Console\Application;

final class Exedoc
{
    private string $cwd;

    public function __construct(?string $cwd = null)
    {
        $this->cwd = $cwd ?? getcwd() ?: throw new RuntimeException('Could not determine cwd');
    }

    public function application(): Application
    {
        $app = new Application('exedoc');
        $app->addCommands([
            $this->commandExecute(),
        ]);

        return $app;
    }

    private function commandExecute(): ExecuteCommand
    {
        return new ExecuteCommand(
            $this->finder(),
            $this->createBlockExecutor(
            )
        );
    }

    private function createBlockExecutor(): MainBlockExecutor
    {
        return new MainBlockExecutor([
            new CreateFileExecutor($this->createProjectFilesystem()),
            new TextBlockExecutor(),
            new ShellBlockExecutor(),
        ]);

    }

    private function finder(): ArticleFinder
    {
        return new ArticleFinder();
    }

    private function createProjectFilesystem(): ProjectFilesystem
    {
        return new ProjectFilesystem($this->cwd);
    }
}
