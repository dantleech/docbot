<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Extension\Core\Block\TextBlock;

final class Article implements Block
{
    /**
     * @param Block[] $blocks
     */
    public function __construct(public string $title, public array $blocks)
    {
    }

    /**
     * @param list<string|Block> $blocks
     */
    public static function create(string $title, array $blocks): Article
    {
        return new self($title, array_map(function (string|Block $block) {
            if (is_string($block)) {
                return new TextBlock($block);
            }
            return $block;
        }, $blocks));
    }

    public function describe(): string
    {
        return sprintf('article "%s" with %d steps', $this->title, count($this->blocks));
    }
}
