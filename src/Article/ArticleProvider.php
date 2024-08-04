<?php

namespace DTL\Docbot\Article;

/**
 * @template TArticleSource of ArticleSource
 */
interface ArticleProvider
{
    /**
     * @return class-string<TArticleSource>
     */
    public static function for(): string;

    /**
     * @param TArticleSource $source
     */
    public function provide(ArticleSource $source): Articles;
}
