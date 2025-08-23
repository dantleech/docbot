<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

/**
 * This block will execute a command on the shell
 * within the workspace directory.
 *
 * The output contains the stdout and stderr and can
 * be validated when this block is nested within an
 * assertion block.
 */
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
        public bool $stdout = true,
        public bool $stderr = false,
    ) {
    }

    public function describe(): string
    {
        return sprintf('%s', trim($this->content));
    }

    public static function name(): string
    {
        return 'shell';
    }
}
