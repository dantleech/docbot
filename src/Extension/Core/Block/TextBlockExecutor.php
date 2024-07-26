<?php

namespace Dantleech\Exedoc\Extension\Core\Block;

use Dantleech\Exedoc\Model\Block;
use Dantleech\Exedoc\Model\Block\BlockExecutor;
use Dantleech\Exedoc\Model\MainBlockExecutor;

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
}
