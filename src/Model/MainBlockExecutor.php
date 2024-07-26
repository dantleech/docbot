<?php

namespace DTL\Docbot\Model;

use DTL\Docbot\Model\Block\BlockExecutor;
use DTL\Docbot\Model\Error\AssertionFailed;
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
    public function __construct(array $executors)
    {
        foreach ($executors as $executor) {
            $this->executors[$executor::for()] = $executor;
        }
    }

    public function execute(Block $block): void
    {
        if (!isset($this->executors[$block::class])) {
            throw new RuntimeException(sprintf(
                'No executor for block: %s',
                $block::class
            ));
        }

        $executor = $this->executors[$block::class];
        try {
            $executor->execute($this, $block);
        } catch (AssertionFailed $failed) {
            throw new AssertionFailed(sprintf(
                'Assertion failed for %s: %s',
                $block->describe(),
                $failed->getMessage(),
            ), 0, $failed);
        }
    }
}
