<?php

namespace DTL\Docbot\Article;

use DTL\Docbot\Article\Error\AssertionFailed;
use DTL\Docbot\Dispatcher\ClosureListenerProvider;
use DTL\Docbot\Dispatcher\EventDispatcher;
use DTL\Docbot\Event\BlockPostExecute;
use DTL\Docbot\Event\BlockPreExecute;
use Psr\EventDispatcher\EventDispatcherInterface;
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
    public function __construct(array $executors, private BlockDataBuffer $buffer, private EventDispatcherInterface $dispatcher)
    {
        foreach ($executors as $executor) {
            $this->executors[$executor::for()] = $executor;
        }
    }

    /**
     * @param list<BlockExecutor<Block>> $executors
     */
    public static function create(array $executors = [], ?BlockDataBuffer $buffer = null, ?EventDispatcherInterface $dispatcher = null): self
    {
        return new self(
            $executors,
            $buffer ?? new BlockDataBuffer(),
            $dispatcher ?? new EventDispatcher(new ClosureListenerProvider(fn () => [])),
        );

    }


    public function execute(Articles $articles, Block $block): BlockData
    {
        $executor = $this->resolveExecutor($block);

        try {
            $this->dispatcher->dispatch(new BlockPreExecute($block));
            $blockData = $executor->execute($this, $articles, $block);
            $this->dispatcher->dispatch(new BlockPostExecute($block, $blockData));
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

    /**
     * @return BlockExecutor<Block>
     */
    private function resolveExecutor(Block $block): BlockExecutor
    {
        // allow blocks to execute themselves
        if ($block instanceof BlockExecutor) {
            return $block;
        }

        if (!isset($this->executors[$block::class])) {
            throw new RuntimeException(sprintf(
                'No executor for block: %s',
                $block::class
            ));
        }

        $executor = $this->executors[$block::class];
        return $executor;
    }
}
