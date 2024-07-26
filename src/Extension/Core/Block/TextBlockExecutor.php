<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Model\Block;
use DTL\Docbot\Model\Block\BlockExecutor;
use DTL\Docbot\Model\MainBlockExecutor;

/**
 * @implements BlockExecutor<Block>
 */
final class TextBlockExecutor implements BlockExecutor
{
    public static function for(): string
    {
        return TextBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Block $block): void
    {
    }

    public function rollback(MainBlockExecutor $executor, Block $block): void
    {
    }
}
