<?php

namespace DTL\Docbot\Attributes;

use Attribute;
use DTL\Docbot\Article\Block;

#[Attribute]
final readonly class Example
{
    public function __construct(
        public string $title,
        public string $description,
        public Block $block
    )
    {
    }
}
