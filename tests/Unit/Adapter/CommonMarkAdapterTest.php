<?php

namespace Dantleech\Exedoc\Tests\Unit\Adapter;

use Dantleech\Exedoc\Adapter\CommonMarkAdapter;
use Dantleech\Exedoc\Model\Block;
use PHPUnit\Framework\TestCase;

class CommonMarkAdapterTest extends TestCase
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
    }
}
