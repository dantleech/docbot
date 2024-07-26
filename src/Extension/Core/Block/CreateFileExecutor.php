<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\Block\BlockExecutor;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Article\ProjectFilesystem;

/**
 * @implements BlockExecutor<CreateFileBlock>
 */
final class CreateFileExecutor implements BlockExecutor
{
    public function __construct(private ProjectFilesystem $filesystem)
    {
    }

    public static function for(): string
    {
        return CreateFileBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Block $block): void
    {
        $this->filesystem->createFile($block->path, $block->content);
    }

    public function rollback(MainBlockExecutor $executor, Block $block): void
    {
        $this->filesystem->remove($block->path);
    }
}
