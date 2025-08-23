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
        if ($block->dependsOn !== null) {
            $executor->execute($articles, $articles->get($block->dependsOn));
        }
        foreach ($block->blocks as $block) {
            $executor->execute($articles, $block);
        }

        return new NoBlockData();
    }
}
