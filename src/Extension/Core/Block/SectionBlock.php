<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Model\Block;

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
