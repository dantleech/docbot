<?php

namespace DTL\Docbot\Article\Provider;

use Closure;
use DTL\Docbot\Article\ArticleSource;
use DTL\Docbot\Article\Articles;

final class ClosureArticleSource implements ArticleSource
{
    /**
     * @param Closure():Articles $closure
     */
    public function __construct(public Closure $closure)
    {
    }
}
