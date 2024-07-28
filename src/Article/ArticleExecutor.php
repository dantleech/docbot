<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Block\NoBlockData;

/**
 * @implements BlockExecutor<Article>
 */
final class ArticleExecutor implements BlockExecutor
{
    public static function for(): string
    {
        return Article::class;
    }

    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData
    {
        foreach ($block->blocks as $block) {
            $executor->execute($articles, $block);
        }

        return new NoBlockData();
    }
}
