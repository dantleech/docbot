<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Error\AssertionFailed;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Environment\Workspace;
use Symfony\Component\Process\Process;

/**
 * @implements BlockExecutor<ShellBlock>
 */
final class ShellExecutor implements BlockExecutor
{
    public function __construct(private Workspace $workspace)
    {
    }

    public static function for(): string
    {
        return ShellBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData
    {
        $process = Process::fromShellCommandline($block->content, $this->workspace->path(), env: $block->env);
        $exitCode = $process->run();

        if ($exitCode !== $block->assertExitCode) {
            throw AssertionFailed::create(
                sprintf(
                    'expected exit code to be %d but got %d: STDOUT: %s, STDERR: %s',
                    $block->assertExitCode,
                    $exitCode,
                    $process->getOutput(),
                    $process->getErrorOutput(),
                )
            );
        }

        return new ShellBlockData($process->getOutput(), $process->getErrorOutput());
    }
}
