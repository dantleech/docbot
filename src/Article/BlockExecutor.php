<?php

namespace DTL\Docbot\Article;

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
    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData;
}
