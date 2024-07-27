<?php

namespace DTL\Docbot\Event;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;

final readonly class BlockPostExecute
{
    public function __construct(public Block $block, public BlockData $data)
    {
    }
}
