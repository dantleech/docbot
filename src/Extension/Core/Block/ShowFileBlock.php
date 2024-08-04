<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

/**
 * Display the contents of a file relative to the workspace.
 *
 * This block can be used to show generated content.
 */
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
        return 'show_file';
    }
}
