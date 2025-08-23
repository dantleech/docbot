<?php

namespace DTL\Docbot\Tests\Unit\Extension\Core\Console;

use DTL\Docbot\Extension\Core\CoreExtension;
use DTL\Docbot\Tests\Unit\IntegrationTestCase;
use Symfony\Component\Process\Process;

final class ExecuteCommandTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->workspace()->clean();
    }

    public function testPathNotProvided(): void
    {
        $this->putConfig([]);
        $process = $this->exec(['execute']);
        $process->run();
        self::assertNotEquals(0, $process->getExitCode());
        self::assertStringContainsString('You must either provide a path', $process->getErrorOutput());
    }

    public function testSpecifyArticles(): void
    {
        $this->putConfig([]);
        $process = $this->exec([
            'execute',
            '--article=foobar',
        ]);
        $process->run();
        self::assertNotEquals(0, $process->getExitCode());
        self::assertStringContainsString('Article "foobar" not known, known articles: ""', $process->getErrorOutput());
    }

    public function testPathProvidedInConfig(): void
    {
        $this->putConfig([
            CoreExtension::PARAM_PATHS => $this->workspace()->path('docs'),
        ]);
        $this->workspace()->createDir('docs');
        $process = $this->exec(['execute']);
        $process->mustRun();
        $this->addToAssertionCount(1);
    }

    public function testNoArticlesFound(): void
    {
        $this->workspace()->createDir('docs');
        $process = $this->exec(['execute', 'docs']);
        $process->mustRun();
        self::assertStringContainsString(
            'No articles found in path(s): "docs"',
            $process->getErrorOutput()
        );
    }

    /**
     * @param array<string,mixed> $array
     */
    private function putConfig(array $array): void
    {
        if (!$array) {
            return;
        }

        $this->workspace()->createFile('.docbot.json', (string)json_encode($array));
    }

    /**
     * @param list<string> $args
     */
    private function exec(array $args): Process
    {
        $process = new Process([
            __DIR__ . '/../../../../../bin/docbot',
            ...$args
        ], $this->workspace()->path());
        return $process;
    }
}
