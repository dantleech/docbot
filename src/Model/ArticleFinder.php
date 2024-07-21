<?php

namespace Dantleech\Exedoc\Model;

use Dantleech\Exedoc\Model\Parser\ParseError;
use Dantleech\Exedoc\Model\Parser\SyntaxError;
use Symfony\Component\Finder\Finder;

final class ArticleFinder
{
    public function __construct(private Parser $parser, private string $cwd)
    {
    }
    public function findInPath(string $path): Articles
    {
        $finder = new Finder();
        $finder->in($path);
        $finder->name('*.md');
        $articles = [];
        foreach ($finder->files() as $file) {
            $contents = file_get_contents($file->getPathname());
            try {
                $articles[] = $this->parser->parse($contents);
            } catch (SyntaxError $error) {
                throw new ParseError($file->getPathname(), $error);
            }
        }

        return new Articles($articles);
    }
}
