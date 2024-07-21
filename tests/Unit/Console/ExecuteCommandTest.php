<?php

namespace Dantleech\Exedoc\Tests\Unit\Console;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ExecuteCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $process = Process::fromShellCommandline('bin/exedoc execute docs', __DIR__ . '/../../..');
        $process->mustRun();
    }
}
