<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Error\AssertionFailed;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Environment\Workspace;

/**
 * @implements BlockExecutor<ShowFileBlock>
 */
final class ShowFileExecutor implements BlockExecutor
{
    public function __construct(private Workspace $workspace)
    {
    }
    public static function for(): string
    {
        return ShowFileBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData
    {
        if (!$this->workspace->exists($block->path)) {
            throw new AssertionFailed(sprintf(
                'file "%s" does not exist in %s',
                $block->path,
                $this->workspace->path(),
            ));
        }
        return new ShowFileData($this->workspace->getContents($block->path));
    }
}
