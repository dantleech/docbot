<?php

namespace DTL\Docbot\Tests\Unit\Article\Example;

use DTL\Docbot\Article\Block;

final class ExampleBlock implements Block
{
    public bool $executed = false;

    public function describe(): string
    {
        return 'hello';
    }

    public static function name(): string
    {
        return 'example';
    }
}
