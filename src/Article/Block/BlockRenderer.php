<?php

namespace DTL\Docbot\Article\Block;

use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\FileMap;
use DTL\Docbot\Article\MainBlockRenderer;

interface BlockRenderer
{
    public function render(MainBlockRenderer $renderer, Block $block, BlockData $data): FileMap;
}
