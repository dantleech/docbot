<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Model\Block;

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
