<?php

namespace Dantleech\Exedoc\Tests\Unit\Adapter\Block;

use Dantleech\Exedoc\Model\Block;

class TestBlock implements Block
{
    public function __construct(
        public string $type,
        public bool $foo,
        public string $content,
        public string $language = 'php',
    )
    {
    }
}
