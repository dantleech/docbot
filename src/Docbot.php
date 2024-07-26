<?php

namespace DTL\Docbot;

use DTL\Docbot\Extension\Core\Block\CreateFileExecutor;
use DTL\Docbot\Console\ExecuteCommand;
use DTL\Docbot\Extension\Core\Block\ShellBlockExecutor;
use DTL\Docbot\Extension\Core\Block\TextBlockExecutor;
use DTL\Docbot\Model\ArticleFinder;
use DTL\Docbot\Model\MainBlockExecutor;
use DTL\Docbot\Model\ProjectFilesystem;
use RuntimeException;
use Symfony\Component\Console\Application;

final class Docbot
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
