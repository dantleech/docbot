<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\Block\BlockExecutor;
use DTL\Docbot\Article\Error\AssertionFailed;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Environment\Workspace;
use Symfony\Component\Process\Process;

/**
 * @implements BlockExecutor<ShellBlock>
 */
final class ShellBlockExecutor implements BlockExecutor
{
    public function __construct(private Workspace $workspace)
    {
    }

    public static function for(): string
    {
        return ShellBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Block $block): void
    {
        $process = Process::fromShellCommandline($block->content, $this->workspace->path());
        $exitCode = $process->run();

        if ($exitCode !== $block->exitCode) {
            throw AssertionFailed::create('expected exit code to be %d but got %d');
        }
    }

    public function rollback(MainBlockExecutor $executor, Block $block): void
    {
    }
}
