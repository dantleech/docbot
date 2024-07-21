<?php

namespace Dantleech\Exedoc\Model;

interface BlockFactory
{
    /**
     * @param array<array-key,string> $args
     */
    public function create(string $type, array $args): Block;

    /**
     * @param array<array-key,string> $args
     */
    public function fromDirective(string $blockAlias, array $args): Block;
}
