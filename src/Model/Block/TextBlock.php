<?php

namespace Dantleech\Exedoc\Model\Block;

use Dantleech\Exedoc\Model\Block;
use Dflydev\DotAccessData\Data;

final class TextBlock implements Block
{
    public function __construct(public  string $text)
    {
    }

}
