<?php

namespace DTL\Docbot\Tests\Unit\Extension\ClassInfo\Provider;

use DTL\Docbot\Extension\ClassInfo\Provider\DocblockProseParser;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DocblockProseParserTest extends TestCase
{
    #[DataProvider('provideParse')]
    public function testParse(string $docblock, string $expected): void
    {
        self::assertEquals($expected, DocblockProseParser::parse($docblock));
    }
    /**
     * @return Generator<string,array{string,string}>
     */
    public static function provideParse(): Generator
    {
        yield 'standard' => [
            <<<'DOCBLOCK'
                /**
                 * Hello this is prose
                 */
                DOCBLOCK,
            'Hello this is prose',
        ];
        yield 'empty1' => [
            <<<'DOCBLOCK'
                /** */
                DOCBLOCK,
            '',
        ];
        yield 'empty2' => [
            <<<'DOCBLOCK'
                /**
                */
                DOCBLOCK,
            '',
        ];
        yield 'does not include tags' => [
            <<<'DOCBLOCK'
                /**
                 * Hello this is prose
                 * @param Foo $bar
                 */
                DOCBLOCK,
            'Hello this is prose',
        ];
    }
}

