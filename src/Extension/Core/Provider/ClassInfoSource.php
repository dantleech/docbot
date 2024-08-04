<?php

namespace DTL\Docbot\Extension\Core\Provider;

use Closure;
use DTL\Docbot\Article\ArticleSource;
use DTL\Docbot\Article\Articles;

final class ClassInfoSource implements ArticleSource
{
    /**
     * @param class-string $instanceof
     * @param Closure(ClassInfo):Articles $builder
     */
    public function __construct(
        public string|array $path,
        public string $instanceof,
        public Closure $builder
    ) {
    }
}
