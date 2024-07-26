<?php

namespace Dantleech\Exedoc\Extension\Core\Block;

use Dantleech\Exedoc\Model\Block;
use Dantleech\Exedoc\Model\Block\BlockExecutor;
use Dantleech\Exedoc\Model\MainBlockExecutor;
use Dantleech\Exedoc\Model\ProjectFilesystem;

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
