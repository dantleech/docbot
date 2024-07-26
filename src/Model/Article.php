<?php

namespace Dantleech\Exedoc\Model;

final class Article implements Block
{
    /**
     * @param Block[] $blocks
     */
    public function __construct(public string $title, public array $blocks)
    {
    }

    /**
     * @param Block[] $blocks
     */
    public static function create(string $title, array $blocks): Article
    {
        return new self($title, $blocks);
    }

    public function describe(): string
    {
        return sprintf('article "%s" with %d steps', $this->title, count($this->blocks));
    }
}
