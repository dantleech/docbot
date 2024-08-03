<?php

namespace DTL\Docbot\Config;

final readonly class ConfigFile
{
    /**
     * @param array<string,mixed> $config
     */
    public function __construct(public string $path, public array $config)
    {
    }
}
