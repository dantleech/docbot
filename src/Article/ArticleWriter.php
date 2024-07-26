<?php

namespace DTL\Docbot\Article;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class ArticleWriter
{
    private Filesystem $filesystem;

    public function __construct(private string $outputPath)
    {
        $this->filesystem = new Filesystem();
    }

    public function write(RenderedArticle $article): ArticleWriterResult
    {
        if (!file_exists(dirname($this->outputPath))) {
            $this->filesystem->mkdir(dirname($this->outputPath));
        }

        $writePath = Path::join($this->outputPath, $article->filename);
        $written = file_put_contents($writePath, $article->contents);
        if (false === $written) {
            throw new RuntimeException(sprintf(
                'Could not write file: %s',
                $writePath
            ));
        }

        return new ArticleWriterResult($writePath, $written);
    }
}
