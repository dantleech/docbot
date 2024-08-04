<?php

namespace DTL\Docbot\Article\Provider;

use DTL\Docbot\Article\ArticleProvider;
use DTL\Docbot\Article\ArticleSource;
use DTL\Docbot\Article\Articles;
/**
 * @implements ArticleProvider<ClosureArticleSource>
 */
final class ClosureArticleProvider implements ArticleProvider
{
    public static function for(): string
    {
        return ClosureArticleSource::class;
    }

    public function provide(ArticleSource $source): Articles
    {
        return ($source->closure)();
    }
}
