<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Block\NoBlockData;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Article\ProjectFilesystem;
use DTL\Docbot\Environment\Workspace;

/**
 * @implements BlockExecutor<SectionBlock>
 */
final class SectionExecutor implements BlockExecutor
{
    public function __construct()
    {
    }

    public static function for(): string
    {
        return SectionBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Block $block): BlockData
    {
        foreach ($block->blocks as $block) {
            $executor->execute($block);
        }

        return new NoBlockData();
    }
}
