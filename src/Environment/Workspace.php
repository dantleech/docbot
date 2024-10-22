<?php

namespace DTL\Docbot\Environment;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class Workspace
{
    private Filesystem $util;

    public function __construct(private string $workingDirectory)
    {
        $this->util = new Filesystem();
    }

    public function createFile(string $path, string $contents): int
    {
        $path = $this->path($path);
        $dir = dirname($path);
        if (!file_exists($dir)) {
            $this->util->mkdir($dir);
        }
        $written = file_put_contents($path, $contents);

        if ($written === false) {
            throw new RuntimeException(sprintf(
                'Could not create file: %s',
                $path
            ));
        }

        return $written;
    }

    public function remove(string $path): void
    {
        $this->util->remove($this->path($path));
    }

    public function path(?string $path = null): string
    {
        if (null === $path) {
            return $this->workingDirectory;
        }
        return Path::makeAbsolute($path, $this->workingDirectory);
    }

    public function clean(): void
    {
        if (file_exists($this->workingDirectory)) {
            $this->util->remove($this->workingDirectory);
        }
        $this->util->mkdir($this->workingDirectory);
    }

    public function exists(string $path): bool
    {
        return file_exists($this->path($path));
    }

    public function getContents(string $path): string
    {
        if (!$this->exists($path)) {
            throw new RuntimeException(sprintf(
                'File does not exist at path: %s',
                $path
            ));
        }

        $contents = file_get_contents($this->path($path));

        if (false === $contents) {
            throw new RuntimeException(sprintf(
                'Could not read file contents: %s',
                $path
            ));
        }

        return $contents;
    }

    public function createDir(string $dir): void
    {
        $this->util->mkdir($this->path($dir));
    }
}
