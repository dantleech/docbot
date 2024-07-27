<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Block\NoBlockData;

final class BlockDataBuffer
{
    /**
     * @var array<array-key,BlockData>
     */
    private array $map = [];

    public function register(Block $block, BlockData $blockData): void
    {
        $this->map[spl_object_id($block)] = $blockData;
    }

    public function fetch(Block $block): BlockData
    {
        $id = spl_object_id($block);
        if (!isset($this->map[$id])) {
            // TODO: disallow this
            return new NoBlockData();
        }

        $block = $this->map[$id];
        unset($this->map[$id]);
        return $block;
    }
}
