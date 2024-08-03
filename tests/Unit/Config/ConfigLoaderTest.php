<?php

namespace DTL\Docbot\Tests\Unit\Config;

use DTL\Docbot\Config\ConfigFile;
use DTL\Docbot\Config\ConfigLoader;
use DTL\Docbot\Tests\Unit\IntegrationTestCase;
use RuntimeException;

final class ConfigLoaderTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->workspace()->clean();
    }

    public function testReturnNullIfNoConfigFound(): void
    {
        $config = $this->load();
        self::assertEquals(null, $config);
    }

    public function testLoadConfig(): void
    {
        $this->workspace()->createFile('docbot.json', '{"core.workspace_path":"foo"}');
        $config = $this->load();
        self::assertEquals(['core.workspace_path' => 'foo'], $config->config);
    }

    public function testLoadHiddenConfig(): void
    {
        $this->workspace()->createFile('.docbot.json', '{"core.workspace_path":"foo"}');
        $config = $this->load();
        self::assertEquals(['core.workspace_path' => 'foo'], $config->config);
    }

    public function testExceptionOnInvalidJson(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('JSON');

        $this->workspace()->createFile('docbot.json', 'a{"core.workspace_path":"foo"}');
        $this->load();
    }

    public function testExceptionIfNotArray(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Config must return an array<string,mixed>');

        $this->workspace()->createFile('docbot.json', '12');
        $this->load();
    }

    private function load(): ?ConfigFile
    {
        return (new ConfigLoader($this->workspace()->path()))->load();
    }
}
