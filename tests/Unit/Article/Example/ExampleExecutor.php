<?php

namespace DTL\Docbot\Tests\Unit\Article\Example;

use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Block\NoBlockData;
use DTL\Docbot\Article\MainBlockExecutor;

/**
 * @implements BlockExecutor<ExampleBlock>
 */
final class ExampleExecutor implements BlockExecutor
{
    public static function for(): string
    {
        return ExampleBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData
    {
        return new NoBlockData();
    }
}
