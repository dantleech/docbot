<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Extension\Core\Block\TextBlock;

/**
 * An article represents a page in the documentation.
 */
final class Article implements Block
{
    public int $index = 0;

    /**
     * @var list<Block>
     */
    public array $blocks;


    /**
     * @param array<int,string|Block> $blocks
     */
    public function __construct(public string $id, public string $title = 'untitled', array $blocks = [])
    {
        $this->blocks = array_map(function (Block|string $block) {
            if (is_string($block)) {
                return new TextBlock($block);
            }
            return $block;
        }, $blocks);
    }

    public function setIndex(int $index): void
    {
        $this->index = $index;
    }

    /**
     * @param list<string|Block> $blocks
     */
    public static function create(string $id, ?string $title = null, array $blocks = []): Article
    {
        if ($title === null) {
            $title = $id;
        }
        return new self($id, $title, array_map(function (string|Block $block) {
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

    public static function name(): string
    {
        return 'article';
    }
}
