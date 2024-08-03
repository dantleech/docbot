<?php

namespace DTL\Docbot\Config;

final readonly class ConfigFile
{
    public function __construct(public string $path, public array $config)
    {
    }
}
