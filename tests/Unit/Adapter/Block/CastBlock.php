<?php

declare(strict_types=1);


namespace Dantleech\Exedoc\Tests\Unit\Adapter\Block;

use Dantleech\Exedoc\Model\Block;

final class CastBlock implements Block
{
    public function __construct(public int $code)
    {
    }
}
