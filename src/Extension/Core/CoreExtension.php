<?php

namespace DTL\Docbot\Extension\Core;

use DTL\Docbot\Article\ArticleExecutor;
use DTL\Docbot\Article\ArticleFinder;
use DTL\Docbot\Article\ArticleRenderer;
use DTL\Docbot\Article\ArticleWriter;
use DTL\Docbot\Article\BlockDataBuffer;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Dispatcher\AggregateListenerProvider;
use DTL\Docbot\Dispatcher\EventDispatcher;
use DTL\Docbot\Environment\Workspace;
use DTL\Docbot\Extension\Core\Block\AssertContainsExecutor;
use DTL\Docbot\Extension\Core\Block\CreateFileExecutor;
use DTL\Docbot\Extension\Core\Block\SectionExecutor;
use DTL\Docbot\Extension\Core\Block\ShellExecutor;
use DTL\Docbot\Extension\Core\Block\ShowFileExecutor;
use DTL\Docbot\Extension\Core\Block\TextBlockExecutor;
use DTL\Docbot\Extension\Core\Console\ExecuteCommand;
use DTL\Docbot\Extension\Core\Progress\ProgressListener;
use DTL\Docbot\Extension\Core\Renderer\TwigBlockRenderer;
use DTL\Docbot\Extension\Core\Renderer\TwigExtension;
use DTL\Docbot\Extension\Core\Renderer\TwigRenderer;
use DTL\Docbot\Renderer\TokenReplacer;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use RuntimeException;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class CoreExtension implements Extension
{
    public const VERSION = '0.x';
    public const TAG_BLOCK_EXECUTOR = 'core.block_executor';
    public const TAG_CONSOLE_COMMAND = 'core.console.command';
    public const PARAM_FORMAT_PATHS = 'core.format.paths';
    public const PARAM_FORMAT = 'core.output_format';
    public const PARAM_OUTPUT_PATH = 'core.output_path';
    public const PARAM_WORKSPACE_DIR = 'core.workspace_dir';
    public const TAG_LISTENER_PROVIDER = 'core.listener_provider';
    public const PARAM_PATHS = 'core.paths';

    public function load(ContainerBuilder $container): void
    {
        $this->registerConsole($container);
        $this->registerRunner($container);
        $this->registerRenderer($container);
        $this->registerBlockExecutors($container);
        $this->registerDispatcher($container);
        $this->registerProgress($container);
    }

    public function configure(Resolver $schema): void
    {
        $cwd = (getcwd() ?: throw new RuntimeException(
            'Could not determine CWD'
        ));

        $schema->setTypes([
            self::PARAM_WORKSPACE_DIR => 'string',
            self::PARAM_FORMAT => 'string',
            self::PARAM_OUTPUT_PATH => 'string',
        ]);
        $schema->setDefaults([
            self::PARAM_WORKSPACE_DIR => $cwd . '/workspace',
            self::PARAM_OUTPUT_PATH => $cwd . '/docs',
            self::PARAM_PATHS => [],
            self::PARAM_FORMAT_PATHS => [
                __DIR__ . '/../../../templates',
            ],
            self::PARAM_FORMAT => 'md',
        ]);
    }

    private function registerConsole(ContainerBuilder $container): void
    {
        $container->register(OutputInterface::class, function () {
            return (new ConsoleOutput())->getErrorOutput();
        });

        $container->register(CommandLoaderInterface::class, function (Container $container): CommandLoaderInterface {
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
            return new ContainerCommandLoader($container, $map);
        });
        $container->register(ExecuteCommand::class, function (Container $container): ExecuteCommand {
            return new ExecuteCommand(
                $container->get(ArticleFinder::class),
                $container->get(MainBlockExecutor::class),
                $container->get(ArticleRenderer::class),
                $container->get(Workspace::class),
                $container->get(ArticleWriter::class),
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

            return new MainBlockExecutor(
                $executors,
                $container->get(BlockDataBuffer::class),
                $container->get(EventDispatcherInterface::class),
            );
        });
        $container->register(BlockDataBuffer::class, function (Container $container) {
            return new BlockDataBuffer();
        });

        $container->register(ArticleFinder::class, function (Container $container) {
            return new ArticleFinder($container->parameter(self::PARAM_PATHS)->listOfString());
        });

        $container->register(Workspace::class, function (Container $container) {
            return new Workspace($container->parameter(self::PARAM_WORKSPACE_DIR)->string());
        });
        $container->register(ArticleWriter::class, function (Container $container) {
            return new ArticleWriter(
                $container->parameter(self::PARAM_OUTPUT_PATH)->string()
            );
        });
    }

    private function registerBlockExecutors(ContainerBuilder $container): void
    {
        $container->register(TextBlockExecutor::class, function (Container $container) {
            return new TextBlockExecutor();
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(ShellExecutor::class, function (Container $container) {
            return new ShellExecutor($container->get(Workspace::class));
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(CreateFileExecutor::class, function (Container $container) {
            return new CreateFileExecutor($container->get(Workspace::class));
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(AssertContainsExecutor::class, function (Container $container) {
            return new AssertContainsExecutor();
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(SectionExecutor::class, function (Container $container) {
            return new SectionExecutor();
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(ShowFileExecutor::class, function (Container $container) {
            return new ShowFileExecutor($container->get(Workspace::class));
        }, [
            self::TAG_BLOCK_EXECUTOR => [],
        ]);
        $container->register(ArticleExecutor::class, function (Container $container) {
            return new ArticleExecutor();
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
                $container->get(BlockDataBuffer::class),
            );
        });

        $container->register(Environment::class, function (Container $container) {
            $paths = $container->parameter(self::PARAM_FORMAT_PATHS)->listOfString();

            $env = new Environment(
                new FilesystemLoader($paths),
                [
                    'autoescape' => false,
                    'strict_variables' => true,
                ],
            );

            $env->addExtension(new TwigExtension(
                $container->get(TwigBlockRenderer::class),
                $container->get(TokenReplacer::class),
                $container->parameter(self::PARAM_FORMAT)->string(),
            ));

            return $env;
        });
        $container->register(TokenReplacer::class, function (Container $container): TokenReplacer {
            return new TokenReplacer();
        });

    }

    private function registerDispatcher(ContainerBuilder $container): void
    {
        $container->register(EventDispatcherInterface::class, function (Container $container) {
            $providers = [];
            foreach ($container->getServiceIdsForTag(self::TAG_LISTENER_PROVIDER) as $id => $_) {
                $providers[] = $container->expect($id, ListenerProviderInterface::class);
            }
            return new EventDispatcher(new AggregateListenerProvider($providers));
        });
    }

    private function registerProgress(ContainerBuilder $container): void
    {
        $container->register(ProgressListener::class, function (Container $container) {
            return new ProgressListener($container->get(OutputInterface::class));
        }, [
            self::TAG_LISTENER_PROVIDER => [],
        ]);
    }
}
