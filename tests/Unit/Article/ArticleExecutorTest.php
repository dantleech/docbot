<?php

namespace DTL\Docbot\Tests\Unit\Article;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Article\ArticleExecutor;
use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Tests\Unit\Article\Example\ExampleBlock;
use DTL\Docbot\Tests\Unit\Article\Example\ExampleExecutor;
use PHPUnit\Framework\TestCase;

final class ArticleExecutorTest extends TestCase
{
    public function testExecuteDependency(): void
    {
        /** @phpstan-ignore-next-line */
        $executor = MainBlockExecutor::create([
            new ArticleExecutor(),
            new ExampleExecutor(),
        ]);

        $block = new ExampleBlock();
        $articles = new Articles([
            new Article('goodbye', 'Hello', [
                $block
            ]),
            new Article('hello', 'Hello', [], dependsOn: 'goodbye'),
        ]);

        $executor->execute($articles, $articles->get('hello'));
        self::assertTrue($block->executed);
    }
}
