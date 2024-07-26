<?php

namespace DTL\Docbot\Article;

interface Block
{
    public function describe(): string;

    public static function name(): string;
}
