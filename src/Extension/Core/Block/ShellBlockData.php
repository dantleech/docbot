<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\BlockData;

final readonly class ShellBlockData implements BlockData
{
    public function __construct(public string $stdout, public string $stderr)
    {
    }

}
