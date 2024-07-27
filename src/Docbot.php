<?php

namespace DTL\Docbot;

use DTL\Docbot\Config\ConfigLoader;
use DTL\Docbot\Extension\Core\CoreExtension;
use Phpactor\Container\PhpactorContainer;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

final class Docbot
{
    public function run(): void
    {
        $app = new Application();
        $input = new ArgvInput();
        try {
        $loader = PhpactorContainer::fromExtensions([
            CoreExtension::class,
        ], (new ConfigLoader())->load())->get(CommandLoaderInterface::class);
        } catch (\Throwable $e) {
            $app->renderThrowable($e, new ConsoleOutput());
            exit(127);
        }
        $app->setCommandLoader($loader);

        $app->run();
    }
}
