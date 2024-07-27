<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Error\AssertionFailed;
use RuntimeException;

final class MainBlockExecutor
{
    /**
     * @var array<class-string<Block>,BlockExecutor<Block>>
     */
    private array $executors;

    /**
     * @param list<BlockExecutor<Block>> $executors
     */
    public function __construct(array $executors, private BlockDataBuffer $buffer)
    {
        foreach ($executors as $executor) {
            $this->executors[$executor::for()] = $executor;
        }
    }

    public function execute(Block $block): BlockData
    {
        if (!isset($this->executors[$block::class])) {
            throw new RuntimeException(sprintf(
                'No executor for block: %s',
                $block::class
            ));
        }

        $executor = $this->executors[$block::class];

        try {
            $blockData = $executor->execute($this, $block);
            $this->buffer->register($block, $blockData);
            return $blockData;
        } catch (AssertionFailed $failed) {
            throw new AssertionFailed(sprintf(
                "Assertion failed for block:\n\n  %s\n\n%s",
                $block->describe(),
                ucfirst($failed->getMessage()),
            ), 0, $failed);
        }
    }
}
