<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

final class SectionBlock implements Block
{
    /**
     * @var array<int,Block>
     */
    public array $blocks;

    /**
     * @param array<int,string|Block> $blocks
     */
    public function __construct(public string $title, array $blocks)
    {
        $this->blocks = array_map(function (Block|string $block) {
            if (is_string($block)) {
                return new TextBlock($block);
            }
            return $block;
        }, $blocks);
    }

    public function describe(): string
    {
        return sprintf('Section "%s" with %d blocks', $this->title, count($this->blocks));
    }

    public static function name(): string
    {
        return 'core_section';
    }
}
