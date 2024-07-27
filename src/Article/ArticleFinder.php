<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Article;
use RuntimeException;
use Symfony\Component\Finder\Finder;

final class ArticleFinder
{
    public function findInPath(string $path): Articles
    {
        $finder = new Finder();
        $finder->in($path);
        $finder->name('*.php');
        $articles = [];
        foreach ($finder->files() as $file) {
            $article = require $file;

            if (!$article instanceof Article) {
                throw new RuntimeException(sprintf(
                    'Article "%s" should return an instance of Article but got: %s',
                    $file,
                    get_debug_type($article)
                ));
            }

            $articles[] = $article;
        }

        return new Articles($articles);
    }
}
