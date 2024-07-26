<?php

namespace Dantleech\Exedoc\Extension\Core\Block;

use Dantleech\Exedoc\Model\Block;

final class TextBlock implements Block
{
    public function __construct(public  string $text)
    {
    }

    public function describe(): string
    {
        return $this->text;
    }

}
