<?php

namespace DTL\Docbot\Tests\Unit\Extension\Core\Block;

use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Extension\Core\Block\ShellBlock;
use DTL\Docbot\Extension\Core\Block\ShellBlockData;
use DTL\Docbot\Extension\Core\Block\ShellExecutor;
use DTL\Docbot\Tests\Unit\IntegrationTestCase;

final class ShellExecutorTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->workspace()->clean();
    }
    public function testExecuteWithEnv(): void
    {
        $data = (new ShellExecutor($this->workspace()))->execute(MainBlockExecutor::create(), new Articles(), new ShellBlock(
            'echo -n $FOO',
            env: [
                'FOO' => 'bar',
            ],
        ));
        self::assertInstanceOf(ShellBlockData::class, $data);
        self::assertEquals('bar', $data->stdout);
    }
}
