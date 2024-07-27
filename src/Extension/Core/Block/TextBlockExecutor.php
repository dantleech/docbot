<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Block\NoBlockData;
use DTL\Docbot\Article\MainBlockExecutor;

/**
 * @implements BlockExecutor<TextBlock>
 */
final class TextBlockExecutor implements BlockExecutor
{
    public static function for(): string
    {
        return TextBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Block $block): BlockData
    {
        if ($block->context !== null) {
            $executor->execute($block->context);
        }

        return new NoBlockData();
    }
}
