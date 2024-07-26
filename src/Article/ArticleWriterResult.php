<?php

namespace DTL\Docbot\Article;

final readonly class ArticleWriterResult
{
    public function __construct(public string $path, public int $bytesWritten)
    {
    }

}
