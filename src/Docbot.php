<?php

namespace DTL\Docbot;

use DTL\Docbot\Extension\Core\CoreExtension;
use Phpactor\Container\PhpactorContainer;
use RuntimeException;
use Symfony\Component\Console\Application;

final class Docbot
{
    public function application(): Application
    {
        return PhpactorContainer::fromExtensions([
            CoreExtension::class,
        ], [])->get(Application::class);
    }
}
