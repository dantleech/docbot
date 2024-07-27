<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\BlockData;

final class ShowFileData implements BlockData
{
    public function __construct(public string $contents)
    {
    }
}
