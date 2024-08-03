<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

final class AssertContainsBlock implements Block
{
    public function __construct(
        public Block $block,
        public string $path,
        public string $needle
    ) {
    }

    public function describe(): string
    {
        return sprintf('`%s` should contain %s', $this->block->describe(), $this->needle);
    }

    public static function name(): string
    {
        return 'core_assert_contains';
    }
}
