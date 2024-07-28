<?php

namespace DTL\Docbot\Article\Block;

use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\MainBlockExecutor;

/**
 * @implements BlockExecutor<self>
 */
final readonly class DependsBlock implements Block, BlockExecutor
{
    public function __construct(public string $id)
    {
    }

    public function describe(): string
    {
        return sprintf('Depends on: %s', $this->id);
    }

    public static function name(): string
    {
        return 'core_depends_on';
    }

    public static function for(): string
    {
        return self::class;
    }

    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData
    {
        $executor->execute($articles, $articles->get($block->id));
        return new NoBlockData();
    }
}
