<?php

namespace DTL\Docbot\Event;

use DTL\Docbot\Article\Block;

final readonly class BlockPreExecute
{
    public function __construct(public Block $block)
    {
    }
}
