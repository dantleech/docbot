<?php

namespace Dantleech\Exedoc\Tests\Unit\Adapter;

use Dantleech\Exedoc\Adapter\CommonMarkAdapter;
use Dantleech\Exedoc\Block\CreateFileBlock;
use Dantleech\Exedoc\Model\Article;
use Dantleech\Exedoc\Model\Block;
use Dantleech\Exedoc\Model\Block\SectionBlock;
use Dantleech\Exedoc\Model\Block\TextBlock;
use PHPUnit\Framework\TestCase;

final class CommonMarkAdapterTest extends TestCase
{
    public function testParse(): void
    {
        $article = CommonMarkAdapter::create()->parse(<<<MARKDOWN
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
