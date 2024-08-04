<?php

use DTL\Docbot\Article\Article;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Extension\Core\Block\TextBlock;
use DTL\Docbot\Extension\Core\Provider\ClassInfoSource;
use Phpactor\DocblockParser\Ast\TextNode;

return new ClassInfoSource(
    path: __DIR__ . '/../../../src',
    instanceof: Block::class,
    builder: static function (ClassInfo $class): Article {
        $name = $class->reflection->getMethod('name')->invoke(null);
        $name = $block::name();

        $blocks = [];
        foreach (MarkdownCodeSplitter::split($class->docblock()) as $section) {
            if ($section instanceof CodeSection) {
                $blocks[] = new CodeBlock(
                    content: $section->contents,
                    language: $section->language,
                );
                continue;
            }

            if ($section instanceof TextSection) {
                $blocks[] = new TextBlock($section->toString());
                continue;
            }
        }

        return new Article(sprintf('reference/%s', $name), $blocks);
    },
);
