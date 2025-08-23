<?php

namespace DTL\Docbot\Tests\Unit\Article;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Article\Block;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testCreateTextBlocksFromStrings(): void
    {
        $article = new Article('foo', 'Foo', ['hello', 'goodbye']);
        self::assertCount(2, $article->blocks);
        self::assertInstanceOf(Block::class, $article->blocks[0]);
    }
}
