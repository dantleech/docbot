<?php

namespace DTL\Docbot\Extension\Core\Provider;

use DTL\Docbot\Article\ArticleProvider;
use DTL\Docbot\Article\ArticleSource;
use DTL\Docbot\Article\Articles;
/**
 * @implements ArticleProvider<ClassInfoSource>
 */
class ClassInfoProvider implements ArticleProvider
{
    public static function for(): string
    {
        return ClassInfoSource::class;
    }

    public function provide(ArticleSource $source): Articles
    {
        return new Articles([]);
    }
}
