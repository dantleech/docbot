<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Model\Block;
use DTL\Docbot\Model\Block\BlockExecutor;
use DTL\Docbot\Model\MainBlockExecutor;
use DTL\Docbot\Model\ProjectFilesystem;

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
