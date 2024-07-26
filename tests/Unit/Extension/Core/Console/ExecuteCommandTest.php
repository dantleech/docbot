<?php

namespace DTL\Docbot\Tests\Unit\Extension\Core\Console;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class ExecuteCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $process = Process::fromShellCommandline('bin/docbot execute docs', __DIR__ . '/../../../../..');
        $process->mustRun();
        $this->addToAssertionCount(1);
    }
}
