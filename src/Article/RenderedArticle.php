<?php

namespace DTL\Docbot\Article;

final readonly class RenderedArticle
{
    public function __construct(public string $filename, public string $contents)
    {
    }

    public static function from(string $filename, string $content): self
    {
        return new self($filename, $content);
    }
}
