<?php

namespace DTL\Docbot\Tests\Unit\Renderer;

use DTL\Docbot\Renderer\Error\RenderException;
use DTL\Docbot\Renderer\TokenReplacer;
use PHPUnit\Framework\TestCase;

final class TokenReplacerTest extends TestCase
{
    public function testReplaceTokens(): void
    {
        $obj = new class() {
            public string $foo = '';

            public string $bar = '';
        };
        $obj->foo = 'bar';
        $obj->bar = 'baz';

        self::assertEquals(
            'hello bar baz',
            (new TokenReplacer())->replace('hello %foo% %bar%', $obj)
        );
    }

    public function testThrowExceptionIfUnknownTokenUsed(): void
    {
        $this->expectException(RenderException::class);
        $this->expectExceptionMessage('String uses unknown token(s) "bee", valid token(s): "foo", "bar"');

        $obj = new class() {
            public string $foo = '';

            public string $bar = '';
        };
        $obj->foo = 'bar';
        $obj->bar = 'baz';

        (new TokenReplacer())->replace('hello %bee% %bar%', $obj);
    }
}
