<?php

namespace Dantleech\Exedoc;

use Dantleech\Exedoc\Adapter\CommonMarkAdapter;
use Dantleech\Exedoc\Adapter\ReflectionBlockFactory;
use Dantleech\Exedoc\Block\CreateFileBlock;
use Dantleech\Exedoc\Block\ShellBlock;
use Dantleech\Exedoc\Console\ExecuteCommand;
use Dantleech\Exedoc\Model\ArticleFinder;
use Dantleech\Exedoc\Model\BlockFactory;
use Dantleech\Exedoc\Model\Parser;
use Symfony\Component\Console\Application;

final class Exedoc
{
    public function __construct(private string $cwd)
    {
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
        return new ExecuteCommand($this->finder());
    }

    private function finder(): ArticleFinder
    {
        return new ArticleFinder($this->parser(), $this->cwd);
    }

    private function parser(): Parser
    {
        return CommonMarkAdapter::create($this->directiveFactory());
    }

    private function directiveFactory(): BlockFactory
    {
        return new ReflectionBlockFactory([
            'create' => CreateFileBlock::class,
            'shell' => ShellBlock::class,
        ]);
    }
}
