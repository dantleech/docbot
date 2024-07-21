<?php

namespace Dantleech\Exedoc\Block;

use Dantleech\Exedoc\Model\Block;

final class ShellBlock implements Block
{
    public function __construct(public string $content, public int $exitCode = 0, public ?string $cwd = null)
    {
    }
}
