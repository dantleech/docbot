<?php

namespace Dantleech\Exedoc;

use Dantleech\Exedoc\Adapter\CommonMarkAdapter;
use Dantleech\Exedoc\Console\ExecuteCommand;
use Dantleech\Exedoc\Model\ArticleFinder;
use Dantleech\Exedoc\Model\Parser;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

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
        return CommonMarkAdapter::create();
    }
}
