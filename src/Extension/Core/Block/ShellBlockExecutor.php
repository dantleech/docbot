<?php

namespace Dantleech\Exedoc\Extension\Core\Block;

use Dantleech\Exedoc\Model\Block;
use Dantleech\Exedoc\Model\Block\BlockExecutor;
use Dantleech\Exedoc\Model\Error\AssertionFailed;
use Dantleech\Exedoc\Model\MainBlockExecutor;
use Symfony\Component\Process\Process;

/**
 * @implements BlockExecutor<ShellBlock>
 */
final class ShellBlockExecutor implements BlockExecutor
{
    public static function for(): string
    {
        return ShellBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Block $block): void
    {
        $process = Process::fromShellCommandline($block->content);
        $exitCode = $process->run();

        if ($exitCode !== $block->exitCode) {
            throw AssertionFailed::create('expected exit code to be %d but got %d');
        }
    }

    public function rollback(MainBlockExecutor $executor, Block $block): void
    {
    }
}
