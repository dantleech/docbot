<?php

namespace DTL\Docbot\Model\Block;

use DTL\Docbot\Model\Block;
use DTL\Docbot\Model\MainBlockExecutor;

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
    public function execute(MainBlockExecutor $executor, Block $block): void;

    /**
     * @param TBlock $block
     */
    public function rollback(MainBlockExecutor $executor, Block $block): void;
}
