<?php

namespace DTL\Docbot\Tests\Unit\Adapter;

use DTL\Docbot\Adapter\CommonMarkAdapter;
use DTL\Docbot\Adapter\ReflectionBlockFactory;
use DTL\Docbot\Extension\Core\Block\CreateFileBlock;
use DTL\Docbot\Model\Article;
use DTL\Docbot\Model\Block;
use DTL\Docbot\Extension\Core\Block\SectionBlock;
use DTL\Docbot\Extension\Core\Block\TextBlock;
use PHPUnit\Framework\TestCase;

final class CommonMarkAdapterTest extends TestCase
{
    public function testParse(): void
    {
        $article = CommonMarkAdapter::create(new ReflectionBlockFactory([
            'createFile' => CreateFileBlock::class,
        ]))->parse(<<<MARKDOWN
            # Hello World

            ```php createFile src/foobar.php
            <?php

            echo "hello world";
            ```

            - Foobar
            - Barfoo

            Hello ![foobar](Hello)
            MARKDOWN);
        self::assertInstanceOf(Block::class, $article);

        self::assertEquals(
            new Article([
                new SectionBlock('Hello World', []),
                new CreateFileBlock('src/foobar.php', language: 'php', content: <<<PHP
                    <?php

                    echo "hello world";

                    PHP),
                new TextBlock(<<<MARKDOWN
                    - Foobar
                    - Barfoo
                    MARKDOWN),
                new TextBlock(<<<MARKDOWN
                    Hello ![foobar](Hello)
                    MARKDOWN),
            ]),
            $article
        );
    }
}
