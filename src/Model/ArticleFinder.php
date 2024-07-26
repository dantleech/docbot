<?php

namespace Dantleech\Exedoc\Model;

use RuntimeException;
use Symfony\Component\Finder\Finder;

final class ArticleFinder
{
    public function __construct()
    {
    }

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
                    'Article "%s" should return an instanceof Article but got: %s',
                    $file,
                    get_debug_type($article)
                ));
            }

            $articles[] = $article;
        }

        return new Articles($articles);
    }
}
