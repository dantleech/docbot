<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\Block\BlockExecutor;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Article\ProjectFilesystem;
use DTL\Docbot\Environment\Workspace;

/**
 * @implements BlockExecutor<CreateFileBlock>
 */
final class CreateFileExecutor implements BlockExecutor
{
    public function __construct(private Workspace $workspace)
    {
    }

    public static function for(): string
    {
        return CreateFileBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Block $block): void
    {
        $this->workspace->createFile($block->path, $block->content);
    }

    public function rollback(MainBlockExecutor $executor, Block $block): void
    {
        $this->workspace->remove($block->path);
    }
}
