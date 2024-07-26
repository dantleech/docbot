<?php

namespace Dantleech\Exedoc\Extension\Core\Block;

use Dantleech\Exedoc\Model\Block;

final class ShellBlock implements Block
{
    public function __construct(public string $content, public int $exitCode = 0, public ?string $cwd = null)
    {
    }

    public function describe(): string
    {
        return sprintf(
            '$ %s in "%s" expecting code %d',
            trim($this->content),
            $this->cwd ?? '<cwd>',
            $this->exitCode
        );
    }
}
