<?php

namespace DTL\Docbot\Tests\Unit\Adapter;

use DTL\Docbot\Adapter\ReflectionBlockFactory;
use DTL\Docbot\Tests\Unit\Adapter\Block\CastBlock;
use DTL\Docbot\Tests\Unit\Adapter\Block\TestBlock;
use PHPUnit\Framework\TestCase;

final class ReflectionBlockFactoryTest extends TestCase
{
    public function testCreateBlock(): void
    {
        $block = $this->factory()->create(TestBlock::class, [
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

    public function testCast(): void
    {
        $block = $this->factory()->create(CastBlock::class, ['12']);
        self::assertEquals(12, $block->code);
    }

    public function testNotEnoughArguments(): void
    {
        $this->expectExceptionMessage('Parameter `content` is required');
        $block = $this->factory()->create(TestBlock::class, [
            'type1',
            'language' => 'php',
            true,
        ]);
    }

    public function testOptional(): void
    {
        $block = $this->factory()->create(TestBlock::class, [
            'type1',
            true,
            'content',
        ]);
        self::assertInstanceOf(TestBlock::class, $block);
        self::assertEquals('content', $block->content);
        self::assertEquals('php', $block->language);
    }

    private function factory(): ReflectionBlockFactory
    {
        return (new ReflectionBlockFactory());
    }
}
