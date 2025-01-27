<?php

use DTL\Docbot\Article\Article;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfo;
use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfoSource;
use DTL\Docbot\Extension\Core\Block\TextBlock;

return new ClassInfoSource(
    path: __DIR__ . '/../../src',
    instanceof: Block::class,
    builder: static function (ClassInfo $class): ?Article {
        /** @var string */
        $name = $class->reflection->getMethod('name')->invoke(null);
        $blocks = [];

        $params = [
            sprintf('Class: `%s`', $class->reflection->getName()),
            'Parameters:'
        ];
        $construct = $class->reflection->getMethod('__construct');
        foreach ($construct->getParameters() as $parameter) {
            $params[] = sprintf(
                '- `%s`: `%s%s`',
                $parameter->getName(),
                $parameter->isOptional() ? '?' : '',
                $parameter->getType()?->__toString() ?? 'n/a'
            );
        }
        $blocks[] = new TextBlock(implode("\n", $params));

        if ($class->reflection->getName() === Block::class) {
            return null;
        }

        $prose = $class->docblockProse();
        if (!$prose) {
            throw new RuntimeException(sprintf(
                'Class "%s" has no documentation',
                $class->reflection->getName()
            ));
        }
        $blocks[] = new TextBlock($prose);

        return new Article(
            sprintf('reference/blocks/%s', $name),
            sprintf('Block: `%s`', $name),
            $blocks
        );
    },
);
