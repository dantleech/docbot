<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Block\NoBlockData;
use DTL\Docbot\Article\MainBlockExecutor;

/**
 * @implements BlockExecutor<SectionBlock>
 */
final class SectionExecutor implements BlockExecutor
{
    public static function for(): string
    {
        return SectionBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData
    {
        foreach ($block->blocks as $block) {
            $executor->execute($articles, $block);
        }

        return new NoBlockData();
    }
}
