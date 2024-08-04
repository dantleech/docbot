<?php

namespace DTL\Docbot\Extension\ClassInfo;

use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfoProvider;
use DTL\Docbot\Extension\Core\CoreExtension;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;

final class ClassInfoExtension implements Extension
{
    public function load(ContainerBuilder $container): void
    {
        $container->register(ClassInfoProvider::class, function (Container $container) {
            return new ClassInfoProvider();
        }, [
            CoreExtension::TAG_ARTICLE_PROVIDER => [],
        ]);
    }

    public function configure(Resolver $schema): void
    {
    }
}
