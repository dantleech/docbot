<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

/**
 * Display normal text. A "context" block can additionally be pased, the output of the context will be made available to the text via. palceholders.
 */
final class TextBlock implements Block
{
    public function __construct(public string $text, public ?Block $context = null)
    {
    }

    public function describe(): string
    {
        return $this->text;
    }

    public static function name(): string
    {
        return 'text';
    }
}
