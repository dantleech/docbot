<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\MainBlockExecutor;

/**
 * @template TBlock of Block
 */
interface BlockExecutor
{
    /**
     * @return class-string<TBlock>
     */
    public static function for(): string;

    /**
     * @param TBlock $block
     */
    public function execute(MainBlockExecutor $executor, Block $block): BlockData;
}
