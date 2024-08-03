<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Attributes\Example;


#[Description(<<<EOT
Assert that the output of the given block contains a given string.
EOT)]
#[Example(
    new AssertContainsBlock(
        block: new ShellBlock('echo -n "Hello World!"'),
        path: 'stdout',
        needle: 'Hello World',
    ),
)]
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
