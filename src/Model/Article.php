<?php

namespace Dantleech\Exedoc\Model;

final class Article implements Block
{
    /**
     * @param Block[] $blocks
     */
    public function __construct(public array $blocks)
    {
    }
}
