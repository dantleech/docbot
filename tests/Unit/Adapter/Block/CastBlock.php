<?php

declare(strict_types=1);


namespace DTL\Docbot\Tests\Unit\Adapter\Block;

use DTL\Docbot\Model\Block;

final class CastBlock implements Block
{
    public function __construct(public int $code)
    {
    }

    public function describe(): string
    {
        return '';
    }
}
