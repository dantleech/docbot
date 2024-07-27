<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Block\NoBlockData;
use DTL\Docbot\Article\MainBlockExecutor;
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

    public function execute(MainBlockExecutor $executor, Block $block): BlockData
    {
        $this->workspace->createFile($block->path, $block->content);
        return new NoBlockData();
    }
}
