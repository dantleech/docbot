<?php

namespace DTL\Docbot\Extension\Core;

use DTL\Docbot\Article\ArticleFinder;
use DTL\Docbot\Article\Block\BlockExecutor;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Environment\Workspace;
use DTL\Docbot\Extension\Core\Block\CreateFileExecutor;
use DTL\Docbot\Extension\Core\Block\ShellBlockExecutor;
use DTL\Docbot\Extension\Core\Block\TextBlockExecutor;
use DTL\Docbot\Extension\Core\Console\ExecuteCommand;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Command\Command;

final class CoreExtension implements Extension
{
    public const TAG_BLOCK_EXECUTOR = 'core.block_executor';
    public const TAG_CONSOLE_COMMAND = 'core.console.command';
    private const PARAM_WORKSPACE_DIR = 'core.workspace_dir';


    public function load(ContainerBuilder $container): void
    {
        $this->registerConsole($container);
        $this->registerRunner($container);
        $this->registerBlockExecutors($container);
    }

    public function configure(Resolver $schema): void
    {
        $schema->setTypes([
            self::PARAM_WORKSPACE_DIR => 'string'
        ]);
        $schema->setDefaults([
            self::PARAM_WORKSPACE_DIR => (getcwd() ?: throw new RuntimeException(
                'Could not determine CWD'
            )) . '/workspace',
        ]);
    }

    private function registerConsole(ContainerBuilder $container): void
    {
        $container->register(Application::class, function (Container $container): Application {
            $app = new Application('exedoc');
            $map = [];
            foreach ($container->getServiceIdsForTag(self::TAG_CONSOLE_COMMAND) as $serviceId => $_) {
                if (!class_exists($serviceId)) {
                    throw new RuntimeException(sprintf(
                        'Expected service ID for console command to be it\'s FQN, got: %s',
                        $serviceId
                    ));
                }
                if (!is_subclass_of($serviceId, Command::class)) {
                    throw new RuntimeException(sprintf(
                        'Expected service ID for console command to be it\'s FQN, got: %s',
                        $serviceId
                    ));
                }
                $name = $serviceId::getDefaultName();
                if (null === $name) {
                    throw new RuntimeException(sprintf(
                        'Console command %s must use the `AsCommand` attribute',
                        $serviceId
                    ));
                }
                $map[$name] = $serviceId;
            }
            $loader = new ContainerCommandLoader($container, $map);
            $app->setCommandLoader($loader);
            return $app;
        });
        $container->register(ExecuteCommand::class, function (Container $container): ExecuteCommand {
            return new ExecuteCommand(
                $container->get(ArticleFinder::class),
                $container->get(MainBlockExecutor::class)
            );
        }, [
            self::TAG_CONSOLE_COMMAND => [],
        ]);
    }

    private function registerRunner(ContainerBuilder $container): void
    {
        $container->register(MainBlockExecutor::class, function (Container $container) {
            $executors = [];
            foreach ($container->getServiceIdsForTag(self::TAG_BLOCK_EXECUTOR) as $serviceId => $_) {
                $executors[] = $container->expect($serviceId, BlockExecutor::class);
            }

            return new MainBlockExecutor($executors);
        });

        $container->register(ArticleFinder::class, function (Container $container) {
            return new ArticleFinder();
        });

        $container->register(Workspace::class, function (Container $container) {
            return new Workspace($container->parameter(self::PARAM_WORKSPACE_DIR)->string());
        });
    }

    private function registerBlockExecutors(ContainerBuilder $container): void
    {
        $container->register(TextBlockExecutor::class, function (Container $container) {
            return new TextBlockExecutor();
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(ShellBlockExecutor::class, function (Container $container) {
            return new ShellBlockExecutor();
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(CreateFileExecutor::class, function (Container $container) {
            return new CreateFileExecutor($container->get(Workspace::class));
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
    }
}
