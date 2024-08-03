<?php

namespace DTL\Docbot\Tests\Unit\Article\Block;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Article\ArticleExecutor;
use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block\DependsBlock;
use DTL\Docbot\Article\MainBlockExecutor;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class DependsBlockTest extends TestCase
{
    public function testExecutesDependencies(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('if-here-then-it-works');

        $articles = new Articles([
            Article::create('one', blocks: [
                new DependsBlock('if-here-then-it-works'),
            ]),
            Article::create('two'),
        ]);

        /** @phpstan-ignore-next-line */
        MainBlockExecutor::create([
            new ArticleExecutor(),
        ])->execute($articles, new DependsBlock('one'));
    }
}
