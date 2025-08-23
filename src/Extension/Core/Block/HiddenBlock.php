<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

/**
 * Any blocks nested within this one will not be rendered as documentation.
 */
final class HiddenBlock implements Block
{
    public function __construct(public Block $block)
    {
    }

    public function describe(): string
    {
        return sprintf('hidden: %s', $this->block->describe());
    }

    public static function name(): string
    {
        return 'hidden';
    }
}
