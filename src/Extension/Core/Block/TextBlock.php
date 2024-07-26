<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Model\Block;

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
