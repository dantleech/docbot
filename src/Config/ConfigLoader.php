<?php

namespace DTL\Docbot\Config;

use RuntimeException;

final class ConfigLoader
{
    public function __construct(private string $cwd)
    {
    }

    public function load(): ?ConfigFile
    {
        $candidates = ['docbot', '.docbot'];

        foreach ($candidates as $candidate) {
            $path = sprintf('%s/%s.json', $this->cwd, $candidate);
            if (!file_exists($path)) {
                continue;
            }

            $contents = file_get_contents($path);

            if (false === $contents) {
                return null;
            }

            $data = json_decode($contents, true);
            if (null === $data) {
                throw new RuntimeException(sprintf(
                    'Could not decode JSON file: %s',
                    $path
                ));
            }

            if (!is_array($data)) {
                throw new RuntimeException(sprintf(
                    'Config must return an array<string,mixed>, got: %s',
                    get_debug_type($data)
                ));
            }

            // ensure the config is array<string,mixed>
            $config = [];
            foreach ($data as $key => $value) {
                if (!is_string($key)) {
                    throw new RuntimeException(sprintf(
                        'All config keys must be strings, got a : %s',
                        get_debug_type($key)
                    ));
                }
                $config[$key] = $value;
            }

            return new ConfigFile($path, $config);
        }

        return null;
    }
}
