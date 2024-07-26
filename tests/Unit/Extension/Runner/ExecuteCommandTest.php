<?php

namespace DTL\Docbot\Tests\Unit\Extension\Runner;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class ExecuteCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $process = Process::fromShellCommandline('bin/exedoc execute docs', __DIR__ . '/../../..');
        $process->mustRun();
        $this->addToAssertionCount(1);
    }
}
