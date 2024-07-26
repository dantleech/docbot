<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\BlockData;

class ShowFileData implements BlockData
{
    public function __construct(public string $contents)
    {
    }
}
