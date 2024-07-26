<?php

namespace Dantleech\Exedoc\Extension\Core\Block;

use Dantleech\Exedoc\Model\Block;

final readonly class SectionBlock implements Block
{
    /**
     * @param Block[] $blocks
     */
    public function __construct(public string $string, public array $blocks)
    {
    }

    public function describe(): string
    {
        return sprintf('Section: %s', $this->string);
    }

}
