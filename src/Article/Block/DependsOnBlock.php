<?php

namespace DTL\Docbot\Article\Block;

use DTL\Docbot\Article\Block;

final readonly class DependsOnBlock implements Block
{
    public function __construct(public string $id)
    {
    }

    public function describe(): string
    {
        return sprintf('Depends on: %s', $this->id);
    }

    public static function name(): string
    {
        return 'core_depends_on';
    }
}
