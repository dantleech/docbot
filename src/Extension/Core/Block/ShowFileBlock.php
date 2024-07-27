<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

final class ShowFileBlock implements Block
{
    public function __construct(public string $path, public string $language = 'text')
    {
    }

    public function describe(): string
    {
        return sprintf('Displaying file %s', $this->path);
    }

    public static function name(): string
    {
        return 'core_show_file';
    }
}
