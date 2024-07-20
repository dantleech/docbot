<?php

namespace Dantleech\Exedoc\Tests\Unit\Adapter;

use Dantleech\Exedoc\Adapter\ReflectionBlockFactory;
use Dantleech\Exedoc\Tests\Unit\Adapter\Block\TestBlock;
use PHPUnit\Framework\TestCase;

class ReflectionBlockFactoryTest extends TestCase
{
    public function testCreateBlock(): void
    {
        $block = (new ReflectionBlockFactory())->create(TestBlock::class, [ 
            'content' => 'type',
            'type1',
            'language' => 'php',
            true,

        ]);

        self::assertEquals(new TestBlock(
            type: 'type1',
            foo: true,
            content: 'type',
            language: 'php',
        ), $block);
    }

    public function testNotEnoughArguments(): void
    {
        $this->expectExceptionMessage('Parameter `content` is required');
        $block = (new ReflectionBlockFactory())->create(TestBlock::class, [ 
            'type1',
            'language' => 'php',
            true,
        ]);
    }

    public function testOptional(): void
    {
        $block = (new ReflectionBlockFactory())->create(TestBlock::class, [ 
            'type1',
            true,
            'content',
        ]);
        self::assertInstanceOf(TestBlock::class, $block);
        self::assertEquals('content', $block->content);
        self::assertEquals('php', $block->language);
    }
}
