<?php

namespace DTL\Docbot\Tests\Unit;

use DTL\Docbot\Environment\Workspace;
use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    public function workspace(): Workspace
    {
        return new Workspace(__DIR__ . '/../Workspace');
    }
}
