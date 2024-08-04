<?php

namespace DTL\Docbot\Extension\ClassInfo\Provider;

use Closure;
use DTL\Docbot\Article\ArticleSource;
use DTL\Docbot\Article\Article;

final class ClassInfoSource implements ArticleSource
{
    /**
     * @param string|list<string> $path
     * @param class-string $instanceof
     * @param Closure(ClassInfo):?Article $builder
     */
    public function __construct(
        public string|array $path,
        public string $instanceof,
        public Closure $builder
    ) {
    }
}
