<?php

namespace DTL\Docbot\Tests\Unit\Adapter\Block;

use DTL\Docbot\Model\Block;

final class TestBlock implements Block
{
    public function __construct(
        public string $type,
        public bool $foo,
        public string $content,
        public string $language = 'php',
    ) {
    }

    public function describe(): string
    {
        return 'test';
    }
}
