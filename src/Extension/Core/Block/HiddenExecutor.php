<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Block\NoBlockData;
use DTL\Docbot\Article\MainBlockExecutor;

/**
 * @implements BlockExecutor<HiddenBlock>
 */
final class HiddenExecutor implements BlockExecutor
{
    public static function for(): string
    {
        return HiddenBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData
    {
        $executor->execute($articles, $block->block);

        return new NoBlockData();
    }
}
