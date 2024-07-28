<?php

namespace DTL\Docbot\Tests\Unit\Article;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block\DependsBlock;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ArticlesTest extends TestCase
{
    public function testIndexAndSort(): void
    {
        $articles = new Articles([
            Article::create('l1c2', blocks: [
                new DependsBlock('r1'),
            ]),
            Article::create('l2c1', blocks: [
                new DependsBlock('l1c1'),
            ]),
            Article::create('r1', blocks: []),
            Article::create('r2', blocks: []),
            Article::create('l1c1', blocks: [
                new DependsBlock('r1'),
            ]),
        ]);

        $articles = $articles->indexAndSort();
        self::assertEquals(['r1', 'l1c2', 'l1c1', 'l2c1', 'r2'], $articles->indexAndSort()->ids());
    }

    public function testExceptionOnCircularDependency(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Dependency cycle found in articles while visiting article "l2c1"');

        $articles = new Articles([
            Article::create('l2c1', blocks: [
                new DependsBlock('l1c1'),
            ]),
            Article::create('r1', blocks: [
                new DependsBlock('l2c1'),
            ]),
            Article::create('l1c1', blocks: [
                new DependsBlock('r1'),
            ]),
        ]);

        $articles = $articles->indexAndSort();
        self::assertEquals(['r1', 'l1c1', 'l2c1', 'l1c2'], $articles->indexAndSort()->ids());
    }
}
