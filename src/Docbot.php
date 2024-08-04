<?php

namespace DTL\Docbot;

use DTL\Docbot\Config\ConfigLoader;
use DTL\Docbot\Extension\ClassInfo\ClassInfoExtension;
use DTL\Docbot\Extension\Core\CoreExtension;
use Phpactor\Container\PhpactorContainer;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

final class Docbot
{
    public function run(): void
    {
        $app = new Application();
        $input = new ArgvInput();
        $output = new ConsoleOutput();
        $output = $output->getErrorOutput();
        $output->writeln(sprintf('Docbot %s by Daniel Leech', CoreExtension::VERSION));

        try {
            $configFile = (new ConfigLoader(
                getcwd() ?: throw new RuntimeException('Could not determine CWD'),
            ))->load();

            if ($configFile !== null) {
                $output->writeln(sprintf('Using config: %s', $configFile->path));
            }
            $loader = PhpactorContainer::fromExtensions([
                CoreExtension::class,
                ClassInfoExtension::class,
            ], $configFile->config ?? [])->get(CommandLoaderInterface::class);
        } catch (Throwable $e) {
            $app->renderThrowable($e, $output);
            exit(127);
        }
        $app->setCommandLoader($loader);

        $app->run();
    }
}
