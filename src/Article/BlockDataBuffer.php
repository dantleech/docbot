<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Block\NoBlockData;
use RuntimeException;

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
            return new NoBlockData();
            throw new RuntimeException(sprintf(
                'Trying to pop data for block that has not been executed: [%s] %s',
                $block::class,
                $block->describe()
            ));
        }

        $block = $this->map[$id];
        unset($this->map[$id]);
        return $block;
    }
}
