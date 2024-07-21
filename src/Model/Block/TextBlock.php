<?php

namespace Dantleech\Exedoc\Model\Block;

use Dantleech\Exedoc\Model\Block;

final class TextBlock implements Block
{
    public function __construct(public  string $text)
    {
    }

}
