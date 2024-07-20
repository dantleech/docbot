<?php

namespace Dantleech\Exedoc\Model;

interface BlockFactory
{
    /**
     * @param array<array-key,scalar> $args
     */
    public function create(string $type, array $args): Block;
}
