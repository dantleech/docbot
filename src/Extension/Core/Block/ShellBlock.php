<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

final class ShellBlock implements Block
{
    /**
     * @param ?array<string,string> $env
     */
    public function __construct(
        public string $content,
        public int $assertExitCode = 0,
        public ?string $cwd = null,
        public ?array $env = null,
    ) {
    }

    public function describe(): string
    {
        return sprintf('%s', trim($this->content));
    }

    public static function name(): string
    {
        return 'core_shell';
    }
}
