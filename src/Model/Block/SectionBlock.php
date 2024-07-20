<?php

namespace Dantleech\Exedoc\Model\Block;

use Dantleech\Exedoc\Model\Block;

final class SectionBlock implements Block
{
    /**
     * @param Block[] $blocks
     */
    public function __construct(private string $string, private array $blocks)
    {
    }

}
