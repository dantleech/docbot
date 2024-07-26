<?php

namespace DTL\Docbot\Extension\Core;

use DTL\Docbot\Article\ArticleFinder;
use DTL\Docbot\Article\ArticleRenderer;
use DTL\Docbot\Article\Block\BlockExecutor;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Environment\Workspace;
use DTL\Docbot\Extension\Core\Block\CreateFileExecutor;
use DTL\Docbot\Extension\Core\Block\ShellBlockExecutor;
use DTL\Docbot\Extension\Core\Block\TextBlockExecutor;
use DTL\Docbot\Extension\Core\Console\ExecuteCommand;
use DTL\Docbot\Extension\Core\Renderer\TwigBlockRenderer;
use DTL\Docbot\Extension\Core\Renderer\TwigExtension;
use DTL\Docbot\Extension\Core\Renderer\TwigRenderer;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Command\Command;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class CoreExtension implements Extension
{
    public const TAG_BLOCK_EXECUTOR = 'core.block_executor';
    public const TAG_CONSOLE_COMMAND = 'core.console.command';
    public const PARAM_FORMAT_PATHS = 'core.format.paths';
    public const PARAM_FORMAT = 'format';

    private const PARAM_WORKSPACE_DIR = 'core.workspace_dir';


    public function load(ContainerBuilder $container): void
    {
        $this->registerConsole($container);
        $this->registerRunner($container);
        $this->registerRenderer($container);
        $this->registerBlockExecutors($container);
    }

    public function configure(Resolver $schema): void
    {
        $schema->setTypes([
            self::PARAM_WORKSPACE_DIR => 'string',
            self::PARAM_FORMAT => 'string',
        ]);
        $schema->setDefaults([
            self::PARAM_WORKSPACE_DIR => (getcwd() ?: throw new RuntimeException(
                'Could not determine CWD'
            )) . '/workspace',
            self::PARAM_FORMAT_PATHS => [
                __DIR__ . '/../../../templates',
            ],
            self::PARAM_FORMAT => 'md',
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
                        'Expected service ID for console command - got: %s',
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
                $container->get(MainBlockExecutor::class),
                $container->get(ArticleRenderer::class)
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
            return new ShellBlockExecutor($container->get(Workspace::class));
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(CreateFileExecutor::class, function (Container $container) {
            return new CreateFileExecutor($container->get(Workspace::class));
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
    }

    private function registerRenderer(ContainerBuilder $container): void
    {
        $container->register(ArticleRenderer::class, function (Container $container) {
            return new TwigRenderer(
                $container->get(Environment::class),
                $container->get(TwigBlockRenderer::class),
                $container->parameter(self::PARAM_FORMAT)->string(),
            );
        });
        $container->register(TwigBlockRenderer::class, function (Container $container) {
            return new TwigBlockRenderer(
            );
        });

        $container->register(Environment::class, function (Container $container) {
            /** @var string[] */
            $paths = $container->parameter(self::PARAM_FORMAT_PATHS)->value();
            $env = new Environment(
                new FilesystemLoader($paths),
                [
                    'autoescape' => false,
                    'strict_variables' => true,
                ],
            );
            $env->addExtension(new TwigExtension(
                $container->get(TwigBlockRenderer::class),
                $container->parameter(self::PARAM_FORMAT)->string(),
            ));
            return $env;
        });
    }
}
