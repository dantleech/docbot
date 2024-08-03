<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Exception\NoPathsProvided;
use RuntimeException;
use Symfony\Component\Finder\Finder;

final class ArticleFinder
{
    /**
     * @param list<string> $paths
     */
    public function __construct(private array $paths)
    {
    }

    public function find(?string $path = null): Articles
    {
        $finder = new Finder();
        $finder->in($this->paths($path));
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

    /**
     * @return list<string> 
     */
    public function paths(?string $path): array
    {
        if ($path !== null) {
            return [$path];
        }
        if (empty($this->paths)) {
            throw new NoPathsProvided(
                'You must either configure paths to scan or provide a path explicitly'
            );
        }

        return $this->paths;
    }
}
