<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Block;

/**
 * Create a file in the workspace at the given path with the given contents.
 */
final readonly class CreateFileBlock implements Block
{
    public function __construct(
        /**
         * Path for the new file relative to the workspace
         */
        public string $path,
        /**
         * Language to use for syntax highlighting (used for rendered documentation)
         */
        public string $language,
        /**
         * Contents of the file
         */
        public string $content,
    ) {
    }

    public function describe(): string
    {
        return sprintf(
            'Creating %s file at "%s" with %d bytes',
            $this->language,
            $this->path,
            strlen($this->content),
        );
    }

    public static function name(): string
    {
        return 'core_create_file';
    }
}
