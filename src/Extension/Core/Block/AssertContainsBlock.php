<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

/**
 * Assert that the value at the given path on the given blocks _output_ contains a string.
 *
 * This can be used to assert, for example, that a shell output's stdout contains a specific string.
 */
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
        return 'assert_contains';
    }
}
